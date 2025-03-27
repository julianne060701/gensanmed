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
        $purchases = PurchaserPO::orderBy('created_at', 'desc')->get();
        $data = [];
    
        foreach ($purchases as $purchase) {
            
            // $btnEdit = '<a href="' . route('admin.purchase.edit', $purchase->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
            //             <i class="fa fa-lg fa-fw fa-pen"></i>
            //             </a>';
    
            // $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" 
            //             title="Delete" data-toggle="modal" data-target="#deleteModalBed" 
            //             data-delete="'. $purchase->id .'" data-name="'. $purchase->name .'">
            //             <i class="fa fa-lg fa-fw fa-trash"></i>
            //             </button>';

            $btnAccept = ($purchase->status !== 'Pending')
            ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Accept Disabled" disabled>
               <i class="fas fa-lg fa-fw fa-check-circle"></i>
            </button>'
            :'<button class="btn btn-xs btn-default text-success mx-1 shadow Accept" 
                title="Accept" data-id="' . $purchase->id . '">
                <i class="fas fa-lg fa-fw fa-check-circle"></i>
            </button>';

    
            $btnDelete = ($purchase->status !== 'Pending')
            ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Decline Disabled" disabled>
               <i class="fas fa-lg fa-fw fa-times-circle"></i>
            </button>'
            :'<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" 
            title="Decline" data-id="' . $purchase->id . '" data-toggle="modal" data-target="#deleteModal">
            <i class="fas fa-lg fa-fw fa-times-circle"></i>
        </button>';

        $btnShow = '<button class="btn btn-xs btn-default text-info mx-1 shadow view-purchase" 
            title="View" data-id="' . $purchase->id . '" data-toggle="modal" data-target="#purchaseModal">
            <i class="fas fa-lg fa-fw fa-eye"></i>
        </button>';

       
    $btnHold = ($purchase->status !== 'Pending')
    ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Hold Disabled" disabled>
       <i class="fas fa-lg fa-fw fa-pause-circle"></i>
    </button>'
    :'<button class="btn btn-xs btn-default text-warning mx-1 shadow Hold" 
        title="Hold" data-id="' . $purchase->id . '" data-toggle="modal" data-target="#holdModal">
        <i class="fas fa-lg fa-fw fa-pause-circle"></i>
    </button>';
    
            // Display a PDF link
            $pdfDisplay = $purchase->image_url 
                ? '<a href="' . asset($purchase->image_url) . '" target="_blank" class="btn btn-primary btn-sm">
                    View PO (PDF)
                   </a>' 
                : 'No PDF';
    
            // Assign colors to status badges
            $statusColors = [
                'Approved' => 'badge-success', // Green
                'Denied' => 'badge-danger', // Red
                'Send to Supplier' => 'badge-warning', // Yellow
                'Pending' => 'badge-secondary', // Default (Gray)
                'Hold' => 'badge-warning' 
            ];
    
            // Ensure status key exists
            $statusBadge = '<span class="badge ' . ($statusColors[$purchase->status] ?? 'badge-secondary') . '">' . $purchase->status . '</span>';
    
            // Build row data
            $rowData = [
                $purchase->id,
                $purchase->po_number,
                $purchase->name,
                $purchase->description ?? 'N/A',
                $statusBadge,
                $pdfDisplay,
                $purchase->created_at->format('m/d/Y'),
                '<nobr>' . $btnShow . $btnAccept . $btnHold . $btnDelete . '</nobr>',
            ];
    
            $data[] = $rowData;
        }
        
        return view('admin.purchase.index', compact('purchases', 'data'));
    }

    public function accept(Request $request)
    {
        $purchase = PurchaserPO::find($request->id);
        
        if ($purchase) {
            $purchase->status = 'Approved'; 
            $purchase->approval_date = now(); 
            $purchase->save();
    
            return response()->json(['success' => 'Purchase order accepted successfully.']);
        }
    
        return response()->json(['error' => 'Purchase order not found.'], 404);
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
        
            $pdfPath = null;
            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $fileName = time() . '_' . $file->getClientOriginalName(); // Unique filename
                $file->move(public_path('po_pdfs'), $fileName); // Store in public/po_pdfs/
                $pdfPath = 'po_pdfs/' . $fileName; // Save relative path
            }
        
            // Save PO data to the database
            PurchaserPO::create([
                'po_number' => $validated['po_number'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'image_url' => $pdfPath, // Store file path
            ]);
        
            return redirect()->route('admin.purchase.index')->with('success', 'PO uploaded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = PurchaserPO::findOrFail($id);
        return response()->json($purchase);
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
    
        if ($request->hasFile('image_url')) {
            // Delete old file if exists
            if ($purchase->image_url && file_exists(public_path($purchase->image_url))) {
                unlink(public_path($purchase->image_url));
            }
    
            // Store new file
            $file = $request->file('image_url');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('po_pdfs'), $fileName);
            $pdfPath = 'po_pdfs/' . $fileName;
        } else {
            $pdfPath = $purchase->image_url;
        }
    
        // Update the purchase order
        $purchase->update([
            'po_number' => $validated['po_number'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'image_url' => $pdfPath,
        ]);
    
        return redirect()->route('admin.purchase.index')->with('success', 'PO updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $purchase = PurchaserPO::find($id);
    
        if (!$purchase) {
            return redirect()->route('purchaser.purchase.index')->with('error', 'Purchase Order not found!');
        }
    
        // Delete the associated file if it exists
        if ($purchase->image_url && file_exists(public_path($purchase->image_url))) {
            unlink(public_path($purchase->image_url));
        }
    
        // Delete the record from the database
        $purchase->delete();
    
        return redirect()->route('purchaser.purchase.index')->with('success', 'PO deleted successfully!');
        
    }

    public function delete(Request $request)
    {
        $purchase = PurchaserPO::find($request->id);

        if ($purchase) {
            $purchase->status = 'Denied'; // Set status to Denied
            $purchase->remarks = $request->remarks; // Save the remarks
            $purchase->save(); // Update record

            return response()->json(['success' => 'Purchase Order denied successfully.']);
        }

        return response()->json(['error' => 'Purchase Order not found.'], 404);
    }

    public function hold(Request $request)
    {
        $purchase = PurchaserPO::find($request->id); // Corrected Model Reference
    
        if ($purchase) {
            $purchase->status = 'Hold';
            $purchase->remarks = $request->remarks;
            $purchase->save();
    
            return response()->json(['success' => 'Purchase Order status updated to Hold.']);
        }
    
        return response()->json(['error' => 'Purchase Order not found.'], 404);
    }


}
