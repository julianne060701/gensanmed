<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaserPO;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\PR;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\PurchaseRequestAccepted;
use Illuminate\Support\Facades\Notification; 

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = PR::orderBy('created_at', 'desc')->get();
        $data = [];
    
        foreach ($purchases as $purchase) {
            $isDisabled = ($purchase->status === 'Denied' || $purchase->status === 'Send to Supplier' || $purchase->status === 'Pending') ? 'disabled' : '';
        

            $btnAccept = ($purchase->status !== 'Pending For Admin')
            ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Accept Disabled" disabled>
               <i class="fas fa-lg fa-fw fa-check-circle"></i>
            </button>'
            :'<button class="btn btn-xs btn-default text-success mx-1 shadow Accept" 
                title="Accept" data-id="' . $purchase->id . '">
                <i class="fas fa-lg fa-fw fa-check-circle"></i>
            </button>';

    
            $btnDelete = ($purchase->status !== 'Pending For Admin')
            ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Decline Disabled" disabled>
               <i class="fas fa-lg fa-fw fa-check-circle"></i>
            </button>'
            :'<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" 
            title="Decline" data-id="' . $purchase->id . '" data-toggle="modal" data-target="#deleteModal">
            <i class="fas fa-lg fa-fw fa-times-circle"></i>
        </button>';

        $btnShow = '<button class="btn btn-xs btn-default text-info mx-1 shadow view-purchase" 
            title="View" data-id="' . $purchase->id . '" data-toggle="modal" data-target="#purchaseModal">
            <i class="fas fa-lg fa-fw fa-eye"></i>
        </button>';

        $btnHold = ($purchase->status !== 'Pending For Admin')
        ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Hold Disabled" disabled>
           <i class="fas fa-lg fa-fw fa-pause-circle"></i>
        </button>'
        :'<button class="btn btn-xs btn-default text-warning mx-1 shadow Hold" 
            title="Hold" data-id="' . $purchase->id . '" data-toggle="modal" data-target="#holdModal">
            <i class="fas fa-lg fa-fw fa-pause-circle"></i>
        </button>';
    
        

        
            // Display a PDF link
            $pdfDisplay = $purchase->attachment_url 
                ? '<a href="' . asset($purchase->attachment_url) . '" target="_blank" class="btn btn-primary btn-sm">
                    View PR (PDF)
                   </a>' 
                : 'No PDF';

            $pdfAdmin = $purchase->admin_attachment 
                ? '<a href="' . asset($purchase->admin_attachment) . '" target="_blank" class="btn btn-primary btn-sm">
                    View Admin Attachment
                   </a>' 
                : 'No PDF';
    
            // Assign colors to status badges
            $statusColors = [
                'Approved' => 'badge-success', // Green
                'Denied' => 'badge-danger', // Red
                'Send to Supplier' => 'badge-warning', // Yellow
                'Pending' => 'badge-secondary', // Default (Gray)
                'Hold' => 'badge-warning', // Gray
                'Pending For PO' => 'badge-warning', // Gray
            ];
    
            // Ensure status key exists
            $statusBadge = '<span class="badge ' . ($statusColors[$purchase->status] ?? 'badge-secondary') . '">' . $purchase->status . '</span>';
    
            // Build row data
            $rowData = [
                $purchase->id,
                $purchase->request_number,
                $purchase->po_number, 
                $purchase->requester_name,
                $purchase->description,
                $statusBadge,
                $pdfDisplay,
                $pdfAdmin,
                $purchase->created_at->format('m/d/Y'),
                $purchase->total_duration > 0 ? $purchase->total_duration . ' ' . Str::plural('day', $purchase->total_duration) : null ,
                '<nobr>' . $btnShow . $btnAccept . $btnHold . $btnDelete . '</nobr>',
            ];
    
            $data[] = $rowData;
        }
     
        return view('admin.purchase_request.index', compact('data'));
    }

    public function accept(Request $request)
    {
        $purchase = PR::find($request->id);
    
        if ($purchase) {
            $purchase->status = 'Pending For PO';
            $purchase->approval_date = now();
            $purchase->save();
    
            return response()->json(['success' => 'Purchase request accepted successfully.']);
        }
    
        return response()->json(['error' => 'Purchase request not found.'], 404);
    }
    
    public function uploadAndAccept(Request $request)
{
    $request->validate([
        'upload_id' => 'required|exists:pr,id',
        'pdf_file' => 'required|mimes:pdf|max:2048',
    ]);

    // Handle file upload
    $file = $request->file('pdf_file');
    $fileName = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('pr_pdfs'), $fileName);
    $filePath = 'pr_pdfs/' . $fileName;

    // Update the PR record with attachment and status
    $purchase = PR::find($request->upload_id);
    if ($purchase) {
        $purchase->admin_attachment = $filePath;
        $purchase->status = 'Pending For PO';
        $purchase->approval_date = now();
        $purchase->save();

        // Notify purchaser
        $purchaser = User::find($purchase->user_id); // Make sure PR has `user_id` field
        if ($purchaser) {
            $purchaser->notify(new PurchaseRequestAccepted($purchase));
        }

        return response()->json(['message' => 'Uploaded and accepted successfully.']);
    }

    return response()->json(['error' => 'Purchase request not found.'], 404);
}
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = PR::findOrFail($id);
        return response()->json($purchase);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function delete(Request $request)
    {
        $purchase = PR::find($request->id);

        if ($purchase) {
            $purchase->status = 'Denied'; // Set status to Denied
            $purchase->remarks = $request->remarks; // Save the remarks
            $purchase->save(); // Update record

            return response()->json(['success' => 'Purchase request denied successfully.']);
        }

        return response()->json(['error' => 'Purchase request not found.'], 404);
    }

    public function hold(Request $request, $id)
    {
        $purchase = PR::findOrFail($id);
        $purchase->status = 'Hold';
        $purchase->remarks = $request->remarks;
        $purchase->save();

        return response()->json(['success' => 'Purchase request status updated to Hold.']);
    }

}
