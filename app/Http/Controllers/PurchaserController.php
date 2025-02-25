<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaserPO;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Controllers\Controller;


class PurchaserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = PurchaserPO::all();
        $data = [];

        foreach ($purchases as $purchase) {
            $btnEdit = '<a href="' . route('admin.purchase.edit', $purchase->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                        </a>';

            $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" title="Delete" data-delete=" " data-toggle="modal" data-target="#deleteModal">
                          <i class="fa fa-lg fa-fw fa-trash"></i>
                          </button>';

            // Display a PDF link instead of an image
            $pdfDisplay = $purchase->image_url 
                ? '<a href="' . asset('storage/' . $purchase->image_url) . '" target="_blank" class="btn btn-primary btn-sm">
                    View PO (PDF)
                   </a>' 
                : 'No PDF';

                $statusColors = [
                    'Approved' => 'badge-success', // Green
                    'Denied' => 'badge-danger', // Red
                    'Send to Supplier' => 'badge-warning', // Yellow
                ];
        
            $rowData = [
                $purchase->id,
                $purchase->po_number,
                $purchase->name,
                $purchase->description ?? 'N/A',
                '<span class="badge ' . ($statusColors[$purchase->status] ?? 'badge-secondary') . '">' . $purchase->status . '</span>',
                $pdfDisplay, 
                $purchase->created_at->format('m/d/Y'),
                '<nobr>' . $btnEdit . $btnDelete . '</nobr>',
            ];
        
            $data[] = $rowData;
        }
        return view('admin.purchase.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('admin.purchase.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
    $validated = $request->validate([
        'po_number' => 'required|integer|min:1|unique:purchaser_po,po_number',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'image_url' => 'nullable|mimes:pdf|max:5120', // Accept only PDFs, max 5MB
    ]);

    // Store the PDF file if provided
    $pdfPath = null;
    if ($request->hasFile('image_url')) {
        $pdfPath = $request->file('image_url')->store('po_pdfs', 'public');
    }

    // Save PO data to the database
    PurchaserPO::create([
        'po_number' => $validated['po_number'],
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'image_url' => $pdfPath, // Store PDF file path
    ]);
    
        return redirect()->route('admin.purchase.index')->with('success', 'PO uploaded successfully!');
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
        $purchase = PurchaserPO::findOrFail($id);
        return view('admin.purchase.edit', compact('purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'po_number' => 'required|integer|min:1|unique:purchaser_po,po_number,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:Pending,Approved,Denied,Send to Supplier',
            'image_url' => 'nullable|mimes:pdf|max:5120', // Accept only PDFs, max 5MB
        ]);
    
        $purchase = PurchaserPO::findOrFail($id);
    
        // Handle PDF upload
        if ($request->hasFile('image_url')) {
            // Delete the old PDF if it exists
            if ($purchase->image_url) {
                Storage::delete('public/' . $purchase->image_url);
            }
    
            // Store the new PDF
            $pdfPath = $request->file('image_url')->store('po_pdfs', 'public');
        } else {
            $pdfPath = $purchase->image_url;
        }
    
        // Update the purchase order
        $purchase->update([
            'po_number' => $validated['po_number'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'image_url' => $pdfPath, // Store PDF file path
        ]);
    
        return redirect()->route('admin.purchase.index')->with('success', 'PO updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
