<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PR;
use App\Notifications\NewPurchaseRequestNotification;
use App\Models\User;
use Illuminate\Support\Str;

class PurchaseRequestController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = PR::where('created_by', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();
        $data = [];
    
        foreach ($purchases as $purchase) {
            $isDisabled = ($purchase->status === 'Denied' || $purchase->status === 'Send to Supplier' || $purchase->status === 'Pending') ? 'disabled' : '';
          
            $btnEdit = '<a href=" " 
            class="btn btn-xs btn-default text-primary mx-1 shadow ' . $isDisabled . '" 
            title="Edit">
            <i class="fa fa-lg fa-fw fa-pen"></i>
            </a>';
    
            $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" 
            title="Delete" data-toggle="modal" data-target="#deleteModalBed" 
            data-delete=" ">
            <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>';

            $btnShow = '<button class="btn btn-xs btn-default text-info mx-1 shadow view-purchase" 
            title="View" data-id="' . $purchase->id . '" data-toggle="modal" data-target="#purchaseModal">
            <i class="fas fa-lg fa-fw fa-eye"></i>
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
                : 'No Attachment From Admin';
            // Assign colors to status badges
            $statusColors = [
                'Approved' => 'badge-success', // Green
                'Denied' => 'badge-danger', // Red
                'Send to Supplier' => 'badge-warning', // Yellow
                'Pending' => 'badge-secondary', // Default (Gray)
                'Hold' => 'badge-danger', // Gray
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
                '<nobr>' . $btnShow . '</nobr>',
            ];
    
            $data[] = $rowData;
        }
    
        return view('head.purchase_request.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('head.purchase_request.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    
     public function store(Request $request)
     {
         // Validate the incoming data
         $validated = $request->validate([
             'request_number' => 'required|integer|min:1|unique:pr,request_number',
             'requester_name' => 'required|string|max:255',
             'description' => 'nullable|string|max:1000',
             'attachment_url' => 'nullable|mimes:pdf|max:5120', // Accept only PDFs, max 5MB
         ]);
     
         // Handle file upload if present
         $pdfPath = null;
         if ($request->hasFile('attachment_url')) {
             $file = $request->file('attachment_url');
             $fileName = time() . '_' . $file->getClientOriginalName(); // Unique filename
             $file->move(public_path('pr_pdfs'), $fileName); // Store in public/pr_pdfs/
             $pdfPath = 'pr_pdfs/' . $fileName; // Save relative path
         }
     
         // Save PR data to the database
         $purchaseRequest = PR::create([
             'request_number' => $validated['request_number'],
             'requester_name' => $validated['requester_name'],
             'description' => $validated['description'] ?? null,
             'attachment_url' => $pdfPath, // Store file path
             'created_by' => auth()->id(), // Store the ID of the authenticated user
             'status' => 'Pending For Admin',
         ]);
     
         if ($purchaseRequest) {
             // Find all admins and notify them
             $admins = User::role('Administrator')->get(); 
             foreach ($admins as $admin) {
                 $admin->notify(new NewPurchaseRequestNotification($purchaseRequest));
             }
     
             return redirect()->route('head.purchase_request.index')->with('success', 'PR uploaded successfully!');
         } else {
             return back()->with('error', 'Failed to create PR.');
         }
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
}
