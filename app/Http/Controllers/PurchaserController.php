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

            // Ensure the image displays correctly
            $imageDisplay = $purchase->image_url
                ? '<img src="' . asset('storage/' . $purchase->image_url) . '" alt="PO Image" width="50" height="50" style="object-fit: cover; border-radius: 5px;">'
                : 'No Image';

            $rowData = [
                $purchase->id,
                $purchase->po_number,
                $purchase->name,
                $purchase->description ?? 'N/A',
                '<span class="badge badge-info">' . $purchase->status . '</span>',
                $imageDisplay,
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
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif,pdf|max:2048',
        ]);
    
        // Debug validation results
        if (!$validated) {
            return back()->withErrors($validated)->withInput();
        }
    
        // Store the image if provided
        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('po_images', 'public');
        }
    
        // Debug incoming data
        \Log::info('Storing Purchase Order:', [
            'po_number' => $validated['po_number'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image_url' => $imagePath,
        ]);
    
        // Save PO data to the database
        $purchaseOrder = PurchaserPO::create([
            'po_number' => $validated['po_number'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image_url' => $imagePath,
        ]);
    
        // Debug database storage
        if (!$purchaseOrder) {
            return back()->with('error', 'Failed to store the PO.')->withInput();
        }
    
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
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $purchase = PurchaserPO::findOrFail($id);

        // Handle image upload
        if ($request->hasFile('image_url')) {
            // Delete the old image if it exists
            if ($purchase->image_url) {
                Storage::delete('public/' . $purchase->image_url);
            }

            // Store the new image
            $imagePath = $request->file('image_url')->store('po_images', 'public');
        } else {
            $imagePath = $purchase->image_url;
        }

        // Update the purchase order
        $purchase->update([
            'po_number' => $validated['po_number'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'image_url' => $imagePath,
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
