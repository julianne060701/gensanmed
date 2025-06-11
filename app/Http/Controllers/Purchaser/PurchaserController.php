<?php

namespace App\Http\Controllers\Purchaser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaserPO;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Notifications\NewPurchaseOrderNotification;
use App\Models\Ticket;
use App\Models\PR;
use Illuminate\Support\Str;

class PurchaserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {

      $newPRCount = PR::where('status', 'Pending For PO')
      ->count();

    $newPOCount = PurchaserPO::where('status', 'Pending')
        ->count();

    $totalPRCount = PR::where('status', 'Approved')
        ->count();
    
    $totalPOCount = PurchaserPO::where('status', 'Send to Supplier')
        ->count();

        return view('purchaser.home', compact(
            'newPRCount', 'newPOCount', 'totalPRCount', 'totalPOCount'
        ));
    }

    public function index()
    {
        $purchases = PurchaserPO::orderBy('created_at', 'desc')->get();
        $data = [];
    
        foreach ($purchases as $purchase) {
            $isDisabled = ($purchase->status === 'Denied' || $purchase->status === 'Send to Supplier' || $purchase->status === 'Pending') ? 'disabled' : '';
          
            $btnEdit = '<a href="' . route('purchaser.purchase.edit', $purchase->id) . '" 
            class="btn btn-xs btn-default text-primary mx-1 shadow ' . $isDisabled . '" 
            title="Edit">
            <i class="fa fa-lg fa-fw fa-pen"></i>
            </a>';
    
            // $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" 
            // title="Delete" data-toggle="modal" data-target="#deleteModalBed" 
            // data-delete="'. $purchase->id .'" data-name="'. $purchase->name .'">
            // <i class="fa fa-lg fa-fw fa-trash"></i>
            // </button>';
        

    
            // Display a PDF link
            $pdfDisplay = $purchase->image_url 
                ? '<a href="' . asset($purchase->image_url) . '" target="_blank" class="btn btn-primary btn-sm">
                    View PO (PDF)
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
                $pdfAdmin,
                $purchase->created_at->format('m/d/Y'),
                $purchase->total_duration > 0 ? $purchase->total_duration . ' ' . Str::plural('day', $purchase->total_duration) : null ,
                '<nobr>' . $btnEdit .  '</nobr>',
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
        // 'po_number' => 'required|integer|min:1|unique:purchaser_po,po_number',
        'po_number' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'image_url' => 'nullable|mimes:pdf|max:20480', // Accept only PDFs, max 20MB
    ]);

    $pdfPath = null;
    if ($request->hasFile('image_url')) {
        $file = $request->file('image_url');
        $fileName = time() . '_' . $file->getClientOriginalName(); // Unique filename
        $file->move(public_path('po_pdfs'), $fileName); // Store in public/po_pdfs/
        $pdfPath = 'po_pdfs/' . $fileName; // Save relative path
    }

    // Save PO data to the database
    $purchaseRequestOrder = PurchaserPO::create([ // Add this line
        'po_number' => $validated['po_number'],
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'image_url' => $pdfPath, // Store file path
    ]);

    if ($purchaseRequestOrder) {
        // Find all admins and notify them
        $admins = User::role('Administrator')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewPurchaseOrderNotification($purchaseRequestOrder));
        }
        

        return redirect()->route('purchaser.purchase.index')->with('success', 'PO uploaded successfully!');
    } else {
        return back()->with('error', 'Failed to create PO.');
    }
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
            'status' => 'required|in:Pending,Approved,Denied,Send to Supplier, Pending For PO',
            'image_url' => 'nullable|mimes:pdf|max:5120', // Accept only PDFs, max 5MB
        ]);

        $purchase = PurchaserPO::findOrFail($id);

        if ($request->hasFile('image_url')) {
            if ($purchase->image_url && file_exists(public_path($purchase->image_url))) {
                unlink(public_path($purchase->image_url));
            }

            $file = $request->file('image_url');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('po_pdfs'), $fileName);
            $pdfPath = 'po_pdfs/' . $fileName;
        } else {
            $pdfPath = $purchase->image_url;
        }

        // Default values for additional fields
        $sendDate = $purchase->send_date;
        $totalDuration = $purchase->total_duration;

        // If status is being updated to "Send to Supplier"
        if ($validated['status'] === 'Send to Supplier') {
            $sendDate = now(); // store current date
            $totalDuration = $purchase->created_at->diffInDays($sendDate);
        }

        // Update fields
        $purchase->update([
            'po_number' => $validated['po_number'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'image_url' => $pdfPath,
            'send_date' => $sendDate,
            'total_duration' => $totalDuration,
        ]);

        return redirect()->route('purchaser.purchase.index')->with('success', 'PO updated successfully!');
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
    
    
    
}
