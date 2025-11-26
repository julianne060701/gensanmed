<?php

namespace App\Http\Controllers\Purchaser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PR;
use App\Notifications\NewPurchaseRequestNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;


class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all purchase requests
        $purchases = PR::whereIn('status', ['Pending for PO', 'Approved'])
            ->orderBy('created_at', 'desc')
            ->get();




        $data = [];

        foreach ($purchases as $purchase) {
            $isDisabled = in_array($purchase->status, ['Denied', 'Send to Supplier', 'Approved']) ? 'disabled' : '';

            // Disable edit if status is Approved
            $btnEdit = ($purchase->status === 'Approved')
                ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Edit Disabled" disabled>
                       <i class="fa fa-lg fa-fw fa-pen"></i>
                   </button>'
                : '<a href="' . route('purchaser.purchase_request.edit', $purchase->id) . '" 
                     class="btn btn-xs btn-default text-primary mx-1 shadow" 
                     title="Edit">
                     <i class="fa fa-lg fa-fw fa-pen"></i>
                   </a>';




            // $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" 
            // title="Delete" data-toggle="modal" data-target="#deleteModalBed" 
            // data-delete=" ">
            // <i class="fa fa-lg fa-fw fa-trash"></i>
            // </button>';

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
                : 'No PDF';
            // Assign colors to status badges
            $statusColors = [
                'Approved' => 'badge-success', // Green
                'Denied' => 'badge-danger', // Red
                'Send to Supplier' => 'badge-warning', // Yellow
                 'Pending For PO' => 'badge-warning', // Gray
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
                $pdfAdmin,
                $purchase->created_at->format('m/d/Y'),
                $purchase->total_duration > 0 ? $purchase->total_duration . ' ' . Str::plural('day', $purchase->total_duration) : null ,
                '<nobr>' . $btnEdit . '</nobr>',
            ];

            $data[] = $rowData;
        }

        return view('purchaser.purchase_request.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $purchase = PR::findOrFail($id);
        return view('purchaser.purchase_request.edit', compact('purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $purchase = PR::findOrFail($id);

        $purchase->request_number = $request->request_number;
        $purchase->po_number = $request->po_number;
        $purchase->requester_name = $request->requester_name;
        $purchase->description = $request->description;

        // If a PO number is provided, update the status and PO_date
        if (!empty($request->po_number)) {
            $purchase->status = 'Approved';
            $purchase->PO_date = now(); // or today() if you only want the date

            // Calculate duration between created_at and PO_date
            $createdDate = Carbon::parse($purchase->created_at);
            $poDate = Carbon::parse($purchase->PO_date);
            $purchase->total_duration = $createdDate->diffInDays($poDate);
        }

        // Handle file upload
        if ($request->hasFile('attachment_url')) {
            $file = $request->file('attachment_url');
            $path = $file->store('attachments', 'public');
            $purchase->attachment_url = $path;
        }

        $purchase->save();

        return redirect()->route('purchaser.purchase_request.index')
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