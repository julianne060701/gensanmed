<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::where('status', '!=', 'Pending')
        ->where('responsible_department', 'Engineer')
        ->orderBy('created_at', 'desc')
        ->get();

        $data = [];
    
        foreach ($tickets as $ticket) {
            
            $btnAccept = ($ticket->status !== 'Approved By Admin')
            ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Accept Disabled" disabled>
               <i class="fas fa-lg fa-fw fa-check-circle"></i>
            </button>'
            : '<button class="btn btn-xs btn-default text-success mx-1 shadow Accept" 
            title="Accept" data-id="' . $ticket->id . '">
           <i class="fas fa-lg fa-fw fa-check-circle"></i>
        </button>';

        $btnCompleted = ($ticket->status !== 'In Progress')
        ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Complete Disabled" disabled>
            <i class="fas fa-lg fa-fw fa-thumbs-up"></i>
           </button>'
        : '<button class="btn btn-xs btn-default text-success mx-1 shadow complete-ticket" 
        title="Completed" data-id="' . $ticket->id . '">
        <i class="fas fa-lg fa-fw fa-thumbs-up"></i>
        </button>';
    
                        
        $btnShow = '<button class="btn btn-xs btn-default text-info mx-1 shadow view-ticket" 
        title="View" data-id="' . $ticket->id . '" data-toggle="modal" data-target="#ticketModal">
        <i class="fa fa-lg fa-fw fa-eye"></i>
    </button>';


    $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete"
    title="Defective" data-id="' . $ticket->id . '" data-toggle="modal" data-target="#deleteModal">
    <i class="fas fa-lg fa-fw fa-times-circle"></i>
</button>';
    
            $pdfDisplay = $ticket->image_url 
                ? '<a href="' . asset($ticket->image_url) . '" target="_blank" class="btn btn-primary btn-sm">
                        View Ticket 
                   </a>' 
                : 'No PDF';
    
            $statusColors = [
                'Approved By Admin' => 'badge-success',
                'Denied' => 'badge-danger',
                'Completed' => 'badge-warning',
                'Defective' => 'badge-danger',
                'Pending' => 'badge-secondary',
                'In Progress' => 'badge-info',
            ];

  
            $rowData = [
                $ticket->ticket_number,
                $ticket->department,
                $ticket->responsible_department,
                $ticket->concern_type,
                $pdfDisplay,
                '<span class="badge ' . ($statusColors[$ticket->status] ?? 'badge-secondary') . '">' . $ticket->status . '</span>',
                $ticket->created_at->format('m/d/Y'),
              '<nobr>' . $btnAccept . $btnCompleted .  $btnDelete . $btnShow  .  '</nobr>',
            ];
            $data[] = $rowData;
        }
        return view('engineer.ticketing.index', compact('data'));
    }

    public function accept($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'In Progress'; // Change status to "In Progress"
        $ticket->approval_date = now(); // Store the approval date
        $ticket->save();
    
        return response()->json(['message' => 'Ticket is now In Progress!']);
    }

    public function complete(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'Completed';
        $ticket->completed_by = $request->completed_by; 
        $ticket->completed_date = now(); 
        $ticket->save();
    
        return response()->json(['message' => 'Ticket marked as Completed!']);
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
    public function delete(Request $request)
    {
        $ticket = Ticket::find($request->id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket request not found.'], 404);
        }
    
        $ticket->status = 'Defective';
        $ticket->responsible_remarks = $request->responsible_remarks;
        $ticket->save();
    
        return response()->json(['success' => 'Ticket marked as defective successfully.']);
    }
}
