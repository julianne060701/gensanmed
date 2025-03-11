<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaserPO;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Controllers\Controller;
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
          
            $btnAccept = '<button class="btn btn-xs btn-default text-success mx-1 shadow Accept" 
    title="Accept" data-id="' . $purchase->id . '">
    <i class="fas fa-lg fa-fw fa-check-circle"></i>
</button>';


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
                '<nobr>' . $btnAccept .$btnEdit . $btnDelete . '</nobr>',
            ];
    
            $data[] = $rowData;
        }
     
        return view('admin.purchase_request.index', compact('data'));
    }

    public function accept(Request $request)
{
    $purchase = PR::find($request->id);
    
    if ($purchase) {
        $purchase->status = 'Approved'; // Set status to approved
        $purchase->save();

        return response()->json(['success' => 'Purchase request accepted successfully.']);
    }

    return response()->json(['error' => 'Purchase request not found.'], 404);
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
