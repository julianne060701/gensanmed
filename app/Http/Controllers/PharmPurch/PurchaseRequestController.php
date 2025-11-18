<?php

namespace App\Http\Controllers\PharmPurch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PR;
use App\Notifications\NewPurchaseRequestNotification;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Show only PRs created by the currently logged-in user
        $purchases = PR::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        $data = [];
    
        foreach ($purchases as $purchase) {
            // Disable edit if status is Approved or Denied
            $isDisabled = in_array($purchase->status, ['Denied', 'Approved']) ? 'disabled' : '';
        
            $btnEdit = ($purchase->status === 'Approved' || $purchase->status === 'Denied')
                ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Edit Disabled" disabled>
                       <i class="fa fa-lg fa-fw fa-pen"></i>
                   </button>'
                : '<a href="' . route('pharmpurch.purchase_request.edit', $purchase->id) . '" 
                     class="btn btn-xs btn-default text-primary mx-1 shadow" 
                     title="Edit">
                     <i class="fa fa-lg fa-fw fa-pen"></i>
                   </a>';

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
                'Pending For Admin' => 'badge-secondary', // Gray
                'Pending For PO' => 'badge-warning', // Yellow
                'Hold' => 'badge-danger', // Red
            ];
    
            // Ensure status key exists
            $statusBadge = '<span class="badge ' . ($statusColors[$purchase->status] ?? 'badge-secondary') . '">' . $purchase->status . '</span>';
    
            // Build row data
            $rowData = [
                $purchase->id,
                $purchase->request_number,
                $purchase->po_number ?? 'N/A', 
                $purchase->requester_name,
                $purchase->description ?? 'N/A',
                $statusBadge,
                $pdfDisplay,
                $pdfAdmin,
                $purchase->created_at->format('m/d/Y'),
                $purchase->total_duration > 0 ? $purchase->total_duration . ' ' . Str::plural('day', $purchase->total_duration) : null,
                '<nobr>' . $btnShow . $btnEdit . '</nobr>',
            ];
    
            $data[] = $rowData;
        }
    
        return view('pharmpurch.purchase_request.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pharmpurch.purchase_request.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'request_number' => 'required|string|max:255',
            'requester_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'attachment_url' => 'nullable|mimes:pdf|max:20480', // Accept only PDFs, max 20MB
        ]);
     
        // Handle file upload if present
        $pdfPath = null;
        if ($request->hasFile('attachment_url')) {
            $file = $request->file('attachment_url');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Unique filename
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('pr_pdfs'))) {
                mkdir(public_path('pr_pdfs'), 0755, true);
            }
            
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
     
            return redirect()->route('pharmpurch.purchase_request.index')->with('success', 'PR uploaded successfully!');
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
        $purchase = PR::findOrFail($id);
        
        // Ensure only the creator can edit
        if ($purchase->created_by !== Auth::id()) {
            return redirect()->route('pharmpurch.purchase_request.index')
                ->with('error', 'You are not authorized to edit this Purchase Request.');
        }
        
        return view('pharmpurch.purchase_request.edit', compact('purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $purchase = PR::findOrFail($id);
        
        // Ensure only the creator can update
        if ($purchase->created_by !== Auth::id()) {
            return redirect()->route('pharmpurch.purchase_request.index')
                ->with('error', 'You are not authorized to update this Purchase Request.');
        }

        $purchase->request_number = $request->request_number;
        $purchase->po_number = $request->po_number;
        $purchase->requester_name = $request->requester_name;
        $purchase->description = $request->description;

        // If a PO number is provided, update the status and PO_date
        if (!empty($request->po_number)) {
            $purchase->status = 'Approved';
            $purchase->PO_date = now();

            // Calculate duration between created_at and PO_date
            $createdDate = Carbon::parse($purchase->created_at);
            $poDate = Carbon::parse($purchase->PO_date);
            $purchase->total_duration = $createdDate->diffInDays($poDate);
        }

        // Handle file upload
        if ($request->hasFile('attachment_url')) {
            // Delete old file if exists
            if ($purchase->attachment_url && file_exists(public_path($purchase->attachment_url))) {
                unlink(public_path($purchase->attachment_url));
            }
            
            $file = $request->file('attachment_url');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('pr_pdfs'))) {
                mkdir(public_path('pr_pdfs'), 0755, true);
            }
            
            $file->move(public_path('pr_pdfs'), $fileName);
            $purchase->attachment_url = 'pr_pdfs/' . $fileName;
        }

        $purchase->save();
    
        return redirect()->route('pharmpurch.purchase_request.index')
            ->with('success', 'Purchase request updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

