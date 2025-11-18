<?php

namespace App\Http\Controllers\PharmPurch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaserPO;
use Illuminate\Support\Facades\Auth;
use App\Models\PR;
use App\Models\User;
use App\Notifications\NewPurchaseOrderNotification;
use Illuminate\Support\Str;

class PharmPurchController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function home()
    {
        $newPRCount = PR::where('status', 'Pending For PO')
            ->where('created_by', Auth::id())
            ->count();

        $newPOCount = PurchaserPO::where('status', 'Pending')
            ->where('created_by', Auth::id())
            ->count();

        $totalPRCount = PR::where('status', 'Approved')
            ->where('created_by', Auth::id())
            ->count();
        
        $totalPOCount = PurchaserPO::where('status', 'Send to Supplier')
            ->where('created_by', Auth::id())
            ->count();

        return view('pharmpurch.home', compact(
            'newPRCount', 'newPOCount', 'totalPRCount', 'totalPOCount'
        ));
    }

    /**
     * Display a listing of purchase orders.
     */
    public function index()
    {
        // Show all POs, but disable actions/attachments for records not created by the logged-in user
        $purchases = PurchaserPO::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        $data = [];
    
        foreach ($purchases as $purchase) {
            $notOwner = $purchase->created_by !== Auth::id();

            // Disable edit if status is final OR if the logged-in user is not the creator
            $statusDisabled = in_array($purchase->status, ['Denied', 'Send to Supplier', 'Pending']);
            $canEdit = !($statusDisabled || $notOwner);

            if ($canEdit) {
                $btnEdit = '<a href="' . route('pharmpurch.purchase.edit', $purchase->id) . '" 
                    class="btn btn-xs btn-default text-primary mx-1 shadow" 
                    title="Edit">
                    <i class="fa fa-lg fa-fw fa-pen"></i>
                </a>';
            } else {
                $btnEdit = '<button class="btn btn-xs btn-default text-muted mx-1 shadow" 
                    title="Edit Disabled" disabled>
                    <i class="fa fa-lg fa-fw fa-pen"></i>
                </button>';
            }
    
            // Display a PDF link - disable link if not the creator
            if ($purchase->image_url) {
                if ($notOwner) {
                    $pdfDisplay = '<button class="btn btn-secondary btn-sm" disabled>
                        View PO (PDF)
                    </button>';
                } else {
                    $pdfDisplay = '<a href="' . asset($purchase->image_url) . '" target="_blank" class="btn btn-primary btn-sm">
                        View PO (PDF)
                    </a>';
                }
            } else {
                $pdfDisplay = 'No PDF';
            }

            if ($purchase->admin_attachment) {
                if ($notOwner) {
                    $pdfAdmin = '<button class="btn btn-secondary btn-sm" disabled>
                        View Admin Attachment
                    </button>';
                } else {
                    $pdfAdmin = '<a href="' . asset($purchase->admin_attachment) . '" target="_blank" class="btn btn-primary btn-sm">
                        View Admin Attachment
                    </a>';
                }
            } else {
                $pdfAdmin = 'No Attachment From Admin';
            }
            
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
                $purchase->total_duration > 0 ? $purchase->total_duration . ' ' . Str::plural('day', $purchase->total_duration) : null,
                '<nobr>' . $btnEdit .  '</nobr>',
            ];
    
            $data[] = $rowData;
        }
        
        return view('pharmpurch.purchase.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pharmpurch.purchase.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'po_number' => 'required|string|max:255',
            'name' => 'required|string|max:255', 
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|mimes:pdf|max:20480', // Accept only PDFs, max 20MB
        ]);
    
        $pdfPath = null;
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Unique filename
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('po_pdfs'))) {
                mkdir(public_path('po_pdfs'), 0755, true);
            }
            
            $file->move(public_path('po_pdfs'), $fileName); // Store in public/po_pdfs/
            $pdfPath = 'po_pdfs/' . $fileName; // Save relative path
        }
    
        try {
            // Save PO data to the database with automatic 'Pending' status
            $purchaseRequestOrder = PurchaserPO::create([
                'po_number' => $validated['po_number'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'image_url' => $pdfPath,
                'status' => 'Pending', // Explicitly set status to 'Pending'
                'created_by' => auth()->id(), // Track who created it
            ]);
    
            // Find all admins and notify them
            $admins = User::role('Administrator')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewPurchaseOrderNotification($purchaseRequestOrder));
            }
            
            return redirect()->route('pharmpurch.purchase.index')
                            ->with('success', 'Purchase Order created successfully with Pending status!');
                            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Failed to create Purchase Order: ' . $e->getMessage());
            
            // Delete uploaded file if database save failed
            if ($pdfPath && file_exists(public_path($pdfPath))) {
                unlink(public_path($pdfPath));
            }
            
            return back()->withInput()
                        ->with('error', 'Failed to create Purchase Order. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchase = PurchaserPO::findOrFail($id);
        
        // Ensure only the creator can edit
        if ($purchase->created_by !== Auth::id()) {
            return redirect()->route('pharmpurch.purchase.index')
                ->with('error', 'You are not authorized to edit this Purchase Order.');
        }
        
        return view('pharmpurch.purchase.edit', compact('purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:Pending,Approved,Denied,Send to Supplier,Pending For PO',
            'image_url' => 'nullable|mimes:pdf|max:20480', // Accept only PDFs, max 20MB
        ]);

        $purchase = PurchaserPO::findOrFail($id);
        
        // Ensure only the creator can update
        if ($purchase->created_by !== Auth::id()) {
            return redirect()->route('pharmpurch.purchase.index')
                ->with('error', 'You are not authorized to update this Purchase Order.');
        }

        if ($request->hasFile('image_url')) {
            if ($purchase->image_url && file_exists(public_path($purchase->image_url))) {
                unlink(public_path($purchase->image_url));
            }

            $file = $request->file('image_url');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('po_pdfs'))) {
                mkdir(public_path('po_pdfs'), 0755, true);
            }
            
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

        return redirect()->route('pharmpurch.purchase.index')->with('success', 'PO updated successfully!');
    }
}

