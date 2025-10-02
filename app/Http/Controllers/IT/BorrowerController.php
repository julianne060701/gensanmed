<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all borrows from the database
        $borrows = Borrow::orderBy('created_at', 'desc')->get();
        
        $data = [];
        
        foreach ($borrows as $borrow) {
            // Calculate total duration
            $totalDuration = 'N/A';
            if ($borrow->borrowed_at && $borrow->returned_at) {
                $borrowedAt = \Carbon\Carbon::parse($borrow->borrowed_at);
                $returnedAt = \Carbon\Carbon::parse($borrow->returned_at);
                $totalDuration = $borrowedAt->diffForHumans($returnedAt, true);
            } elseif ($borrow->borrowed_at) {
                $borrowedAt = \Carbon\Carbon::parse($borrow->borrowed_at);
                $totalDuration = $borrowedAt->diffForHumans(now(), true) . ' (ongoing)';
            }
            
            // Format status
            $statusText = $this->getStatusText($borrow->status);
            $statusClass = $this->getStatusClass($borrow->status);
            
            // Format dates
            $borrowedAtFormatted = $borrow->borrowed_at ? \Carbon\Carbon::parse($borrow->borrowed_at)->format('M d, Y H:i') : 'N/A';
            $returnedAtFormatted = $borrow->returned_at ? \Carbon\Carbon::parse($borrow->returned_at)->format('M d, Y H:i') : 'N/A';
            
            $data[] = [
                'BR-' . str_pad($borrow->id, 3, '0', STR_PAD_LEFT), // Borrow number
                $borrow->borrower_name,
                $borrow->purpose,
                $borrow->location,
                $borrow->type_of_equipment,
                '<span class="badge badge-' . $statusClass . '">' . $statusText . '</span>',
                $this->getActionButtons($borrow)
            ];
        }

        return view('IT.borrow.index', compact('data'));
    }
    
    /**
     * Get status text based on status code
     */
    private function getStatusText($status)
    {
        switch ($status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Approved';
            case 2:
                return 'Borrowed';
            case 3:
                return 'Returned';
            case 4:
                return 'Denied';
            default:
                return 'Unknown';
        }
    }
    
    /**
     * Get status CSS class based on status code
     */
    private function getStatusClass($status)
    {
        switch ($status) {
            case 0:
                return 'warning';
            case 1:
                return 'success';
            case 2:
                return 'info';
            case 3:
                return 'primary';
            case 4:
                return 'danger';
            default:
                return 'secondary';
        }
    }
    
    /**
     * Get action buttons based on borrow status
     */
    private function getActionButtons($borrow)
    {
        $viewButton = '<button class="btn btn-xs btn-default text-info mx-1 shadow view-borrow" data-id="' . $borrow->id . '" title="View Details"><i class="fa fa-lg fa-fw fa-eye"></i></button>';
        
        switch ($borrow->status) {
            case 0: // Pending
                return '<nobr>' . 
                       $viewButton . 
                       '<button class="btn btn-xs btn-default text-success mx-1 shadow Accept" data-id="' . $borrow->id . '" title="Accept Request"><i class="fa fa-lg fa-fw fa-check"></i></button>' .
                       '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" data-id="' . $borrow->id . '" title="Deny Request"><i class="fa fa-lg fa-fw fa-times"></i></button>' .
                       '</nobr>';
                       
            case 1: // Approved
                return '<nobr>' . 
                       $viewButton . 
                       '<button class="btn btn-xs btn-default text-primary mx-1 shadow Return" data-id="' . $borrow->id . '" title="Mark as Returned"><i class="fa fa-lg fa-fw fa-undo"></i></button>' .
                       '</nobr>';
                       
            case 2: // Borrowed
                return '<nobr>' . 
                       $viewButton . 
                       '<button class="btn btn-xs btn-default text-primary mx-1 shadow Return" data-id="' . $borrow->id . '" title="Mark as Returned"><i class="fa fa-lg fa-fw fa-undo"></i></button>' .
                       '</nobr>';
                       
            case 3: // Returned
                return '<nobr>' . 
                       $viewButton . 
                       '</nobr>';
                       
            case 4: // Denied
                return '<nobr>' . 
                       $viewButton . 
                       '</nobr>';
                       
            default:
                return '<nobr>' . 
                       $viewButton . 
                       '</nobr>';
        }
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
        $borrow = Borrow::findOrFail($id);
        
        // Calculate total duration
        $totalDuration = 'N/A';
        if ($borrow->borrowed_at && $borrow->returned_at) {
            $borrowedAt = \Carbon\Carbon::parse($borrow->borrowed_at);
            $returnedAt = \Carbon\Carbon::parse($borrow->returned_at);
            $totalDuration = $borrowedAt->diffForHumans($returnedAt, true);
        } elseif ($borrow->borrowed_at) {
            $borrowedAt = \Carbon\Carbon::parse($borrow->borrowed_at);
            $totalDuration = $borrowedAt->diffForHumans(now(), true) . ' (ongoing)';
        }
        
        return response()->json([
            'id' => 'BR-' . str_pad($borrow->id, 3, '0', STR_PAD_LEFT),
            'borrower_name' => $borrow->borrower_name,
            'purpose' => $borrow->purpose,
            'location' => $borrow->location,
            'type_of_equipment' => $borrow->type_of_equipment,
            'borrowedAt' => $borrow->borrowed_at ? \Carbon\Carbon::parse($borrow->borrowed_at)->format('M d, Y H:i') : 'N/A',
            'returnedAt' => $borrow->returned_at ? \Carbon\Carbon::parse($borrow->returned_at)->format('M d, Y H:i') : 'N/A',
            'totalDuration' => $totalDuration,
            'status' => $this->getStatusText($borrow->status),
            'approval_date' => $borrow->updated_at ? \Carbon\Carbon::parse($borrow->updated_at)->format('M d, Y H:i') : null,
            'borrowApproved' => $borrow->status >= 1 ? \Carbon\Carbon::parse($borrow->updated_at)->format('M d, Y H:i') : null,
            'borrowDeniedRemarks' => null, // Add this field to your migration if needed
        ]);
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
     * Accept a borrow request.
     */
    public function accept(Request $request, string $id)
    {
        try {
            $borrow = Borrow::findOrFail($id);
            
            // Check if already processed
            if ($borrow->status != 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed.'
                ], 400);
            }
            
            // Update status to approved (1)
            $borrow->update([
                'status' => 1,
                'updated_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Borrow request has been approved successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the request.'
            ], 500);
        }
    }
    
    /**
     * Deny a borrow request.
     */
    public function deny(Request $request, string $id)
    {
        try {
            $borrow = Borrow::findOrFail($id);
            
            // Check if already processed
            if ($borrow->status != 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This request has already been processed.'
                ], 400);
            }
            
            // Validate remarks
            $request->validate([
                'remarks' => 'required|string|max:255'
            ]);
            
            // Update status to denied (4) - you can adjust this status code as needed
            $borrow->update([
                'status' => 4, // Assuming 4 = denied, adjust based on your status system
                'remarks' => $request->remarks, // You might need to add this field to your migration
                'updated_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Borrow request has been denied successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the request.'
            ], 500);
        }
    }
    
    /**
     * Mark equipment as returned.
     */
    public function return(Request $request, string $id)
    {
        try {
            $borrow = Borrow::findOrFail($id);
            
            // Check if can be returned (status 1 = approved or 2 = borrowed)
            if (!in_array($borrow->status, [1, 2])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This request cannot be marked as returned.'
                ], 400);
            }
            
            // Update status to returned (3) and set returned_at timestamp
            $borrow->update([
                'status' => 3,
                'returned_at' => now(),
                'updated_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Equipment has been marked as returned successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the request.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
