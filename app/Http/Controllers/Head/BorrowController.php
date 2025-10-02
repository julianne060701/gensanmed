<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrow;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrows = Borrow::orderBy('created_at', 'desc')->get();
        $data = [];
    
        // Map integer statuses to readable labels
        $statusLabels = [
            0 => 'Pending',
            1 => 'Accepted',
            2 => 'Borrowed',
            3 => 'Returned',
        ];
    
        foreach ($borrows as $borrow) {
            // Disable actions if status is finalized
            $isDisabled = in_array($borrow->status, [1, 2, 3]) ? 'disabled' : '';
    
            // Action buttons
            $btnView = '
                <button class="btn btn-xs btn-info view-borrow mx-1 shadow" data-id="'.$borrow->id.'" '.$isDisabled.'>
                    <i class="fa fa-lg fa-fw fa-eye"></i>
                </button>';
    
                $btnEdit = '
                <button class="btn btn-xs btn-success edit-borrow mx-1 shadow" 
                        data-id="'.$borrow->id.'" '.$isDisabled.'>
                    <i class="fa fa-lg fa-fw fa-pen"></i>
                </button>';
            
            
    
    
            // Format borrow/return dates
            $borrowedAt = $borrow->borrowed_at ? Carbon::parse($borrow->borrowed_at)->format('m/d/Y H:i') : '—';
            $returnedAt = $borrow->returned_at ? Carbon::parse($borrow->returned_at)->format('m/d/Y H:i') : '—';
    
            // Duration
            if ($borrow->borrowed_at) {
                $start = Carbon::parse($borrow->borrowed_at);
                $end   = $borrow->returned_at ? Carbon::parse($borrow->returned_at) : now();
    
                $days  = $start->diffInDays($end);
                $hours = $start->diffInHours($end);
    
                if ($days >= 1) {
                    // Show only days
                    $totalDuration = $days . ' ' . Str::plural('day', $days);
                } else {
                    // Show only hours
                    $totalDuration = $hours . ' ' . Str::plural('hour', $hours);
                }
            } else {
                $totalDuration = '—';
            }
    
            // Build row
            $rowData = [
                $borrow->id,
                e($borrow->borrower_name ?? '—'),
                e($borrow->purpose ?? '—'),
                e($borrow->location ?? '—'),
                e($borrow->type_of_equipment ?? '—'),
                $borrowedAt,
                $returnedAt,
                $totalDuration,
                $statusLabels[$borrow->status] ?? 'Unknown',
                '<nobr>' . $btnView . $btnEdit . '</nobr>',
            ];
    
            $data[] = $rowData;
        }
    
        return view('head.borrow.index', compact('data'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lastBorrow = Borrow::latest('id')->first();
        $borrowNumber = $lastBorrow ? $lastBorrow->id + 1 : 1;

        return view('head.borrow.create', compact('borrowNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrower_name'     => 'required|string|max:255',
            'purpose'           => 'required|string|max:500',
            'location'          => 'required|string|max:255',
            'type_of_equipment' => 'required|string|max:255',
            'borrowed_at'       => 'required|date',
            'returned_at'       => 'nullable|date|after_or_equal:borrowed_at',
        ]);

        // Default status = 0 (pending)
        $validated['status'] = 0;

        Borrow::create($validated);

        return redirect()->route('head.borrow.index')
                         ->with('success', 'Borrow request submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $borrow = Borrow::findOrFail($id);
    
        $borrowedAt = $borrow->borrowed_at
            ? \Carbon\Carbon::parse($borrow->borrowed_at)->format('m/d/Y H:i')
            : '—';
    
        $returnedAt = $borrow->returned_at
            ? \Carbon\Carbon::parse($borrow->returned_at)->format('m/d/Y H:i')
            : '—';
    
        // Duration
        if ($borrow->borrowed_at) {
            $start = \Carbon\Carbon::parse($borrow->borrowed_at);
            $end   = $borrow->returned_at ? \Carbon\Carbon::parse($borrow->returned_at) : now();
    
            $days  = $start->diffInDays($end);
            $hours = $start->diffInHours($end);
    
            if ($days >= 1) {
                $totalDuration = $days . ' ' . \Illuminate\Support\Str::plural('day', $days);
            } else {
                $totalDuration = $hours . ' ' . \Illuminate\Support\Str::plural('hour', $hours);
            }
        } else {
            $totalDuration = '—';
        }
    
        return response()->json([
            'id' => $borrow->id,
            'borrower_name' => $borrow->borrower_name,
            'purpose' => $borrow->purpose,
            'location' => $borrow->location,
            'type_of_equipment' => $borrow->type_of_equipment,
            'borrowedAt' => $borrowedAt,
            'returnedAt' => $returnedAt,
            'totalDuration' => $totalDuration,
            'approval_date' => $borrow->approval_date,
            'borrowDeniedRemarks' => $borrow->remarks_by,
            'returned_by' => $borrow->returned_by,
        ]);
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
{
    $borrow = Borrow::findOrFail($id);
    return view('head.borrow.edit', compact('borrow'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'borrower_name'     => 'required|string|max:255',
            'purpose'           => 'required|string|max:500',
            'location'          => 'required|string|max:255',
            'type_of_equipment' => 'required|string|max:255',
            'borrowed_at'       => 'required|date',
            'returned_at'       => 'nullable|date|after_or_equal:borrowed_at',
        ]);

        $borrow = Borrow::findOrFail($id);
        $borrow->update($validated);

        return redirect()->route('head.borrow.index')
                         ->with('success', 'Borrow record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $borrow = Borrow::findOrFail($id);
        $borrow->delete();

        return redirect()->route('head.borrow.index')
                         ->with('success', 'Borrow record deleted successfully!');
    }
}
