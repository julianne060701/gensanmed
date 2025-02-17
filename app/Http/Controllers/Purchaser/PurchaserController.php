<?php

namespace App\Http\Controllers\Purchaser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaserPO;
use Illuminate\Support\Facades\Storage;

class PurchaserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        return view('purchaser.home');
    }

    public function index()
    {
        // Fetch all users
        $users = User::all();
        $data = [];
        
        foreach ($users as $user) {
    
            // Define the Edit button (use the route for editing user)
            $btnEdit = '<a href=" " class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
            </a>';
    
            // Define the Delete button (trigger modal for deletion)
            $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" id="deleteUserID" title="Delete" data-delete="' . $user->id . '" data-toggle="modal" data-target="#deleteModal">
                <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>';
    
            // Build the row data for the DataTable
            $rowData = [
                $user->id,
                $user->name,
                $user->email,
                $user->roles->pluck('name')->implode(', '),  // Assuming roles are set with Spatie
                // $user->status,  // Assuming you have a status field
                $user->created_at->format('m/d/Y'),
                '<nobr>' . $btnEdit . $btnDelete . '</nobr>',  // Action buttons (Edit and Delete)
            ];
    
            $data[] = $rowData;
        }
    
        // Pass the processed data to the view
        return view('admin.user.index', compact('data'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('purchaser.purchase.create');
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
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
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
    
        return redirect()->route('purchaser.purchase.index')->with('success', 'PO uploaded successfully!');
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
        return view('purchaser.purchase.edit', compact('purchase'));
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
            'status' => 'required|string|in:Active,Declined,Inactive',
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

        return redirect()->route('purchaser.purchase.index')->with('success', 'PO updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
