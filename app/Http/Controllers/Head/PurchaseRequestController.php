<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PR;


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
        
            // Display a PDF link
            $pdfDisplay = $purchase->attachment_url 
                ? '<a href="' . asset($purchase->attachment_url) . '" target="_blank" class="btn btn-primary btn-sm">
                    View PR (PDF)
                   </a>' 
                : 'No PDF';
    
            // Assign colors to status badges
            $statusColors = [
                'Approved' => 'badge-success', // Green
                'Denied' => 'badge-danger', // Red
                'Send to Supplier' => 'badge-warning', // Yellow
                'Pending' => 'badge-secondary' // Default (Gray)
            ];
    
            // Ensure status key exists
            $statusBadge = '<span class="badge ' . ($statusColors[$purchase->status] ?? 'badge-secondary') . '">' . $purchase->status . '</span>';
    
            // Build row data
            $rowData = [
                $purchase->id,
                $purchase->request_number,
                $purchase->po_number, 
                $purchase->requester_name,
                $purchase->remarks,
                $statusBadge,
                $pdfDisplay,
                $purchase->created_at->format('m/d/Y'),
                '<nobr>' . $btnEdit . $btnDelete . '</nobr>',
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
            'remarks' => 'nullable|string|max:1000',
            'attachment_url' => 'nullable|mimes:pdf|max:5120', // Accept only PDFs, max 5MB
        ]);
    
        $pdfPath = null;
        if ($request->hasFile('attachment_url')) {
            $file = $request->file('attachment_url');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Unique filename
            $file->move(public_path('pr_pdfs'), $fileName); // Store in public/pr_pdfs/
            $pdfPath = 'pr_pdfs/' . $fileName; // Save relative path
        }
    
        // Save PR data to the database
        PR::create([
            'request_number' => $validated['request_number'],
            'requester_name' => $validated['requester_name'],
            'remarks' => $validated['remarks'] ?? null,
            'attachment_url' => $pdfPath, // Store file path
            'created_by' => auth()->id(), // Store the ID of the authenticated user
        ]);
        return redirect()->route('head.purchase_request.index')->with('success', 'PR uploaded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
