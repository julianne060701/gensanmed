<?php

namespace App\Http\Controllers\Purchaser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaserPO;

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
        $purchases = PurchaserPO::all();
        $data = [];
    
        foreach ($purchases as $purchase) {
           
            $btnEdit = '<a href="" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
            <i class="fa fa-lg fa-fw fa-pen"></i>
            </a>';
            
            $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" id="deletePurchaseID" title="Delete" data-delete="" data-toggle="modal" data-target="#deleteModal">
            <i class="fa fa-lg fa-fw fa-trash"></i>
            </button>';
    
            $rowData = [
                $purchase->id,
                $purchase->name,
                $purchase->po_number,
                $purchase->description ?? 'N/A',
                $purchase->image_url ? '<img src="' . asset('storage/' . $purchase->image_url) . '" width="50">' : 'No Image',
                $purchase->created_at->format('m/d/Y'),
                '<nobr>' . $btnEdit . $btnDelete . '</nobr>',
            ];
    
            $data[] = $rowData;
        }
    
        return view('purchaser.purchase.index', compact('data'));
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
