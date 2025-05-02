<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaserPO;
use App\Models\PR;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class POreportController extends Controller
{
    public function index() {

        $purchases = PurchaserPO::whereIn('status', ['Approved', 'Hold', 'Denied', 'Send to Supplier'])
        ->orderBy('created_at', 'desc')
        ->get();

$data = [];

foreach ($purchases as $purchase) {
$isDisabled = ($purchase->status === 'Denied' || $purchase->status === 'Send to Supplier' || $purchase->status === 'Pending') ? 'disabled' : '';

// Display a PDF link
$pdfDisplay = $purchase->image_url 
    ? '<a href="' . asset($purchase->image_url) . '" target="_blank" class="btn btn-primary btn-sm">
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
    'Pending' => 'badge-secondary', // Default (Gray)
    'Hold' => 'badge-warning', // Gray
    'Pending For PO' => 'badge-warning', // Gray
];

// Ensure status key exists
$statusBadge = '<span class="badge ' . ($statusColors[$purchase->status] ?? 'badge-secondary') . '">' . $purchase->status . '</span>';

// Build row data
$rowData = [
    $purchase->id,
    $purchase->po_number, 
    $purchase->name,
    $purchase->description,     
    $pdfDisplay,
    $pdfAdmin,
    $purchase->remarks,
    Carbon::parse($purchase->created_at)->format('m/d/Y'),
    $purchase->approval_date ? Carbon::parse($purchase->approval_date)->format('m/d/Y') : null,
    $purchase->send_date ? Carbon::parse($purchase->send_date)->format('m/d/Y') : null,
    $purchase->total_duration > 0 ? $purchase->total_duration . ' ' . Str::plural('day', $purchase->total_duration) : null ,
    $statusBadge,
];

$data[] = $rowData;
}
        return view('admin.reports.purchase_order', compact('data'));
    }
}
