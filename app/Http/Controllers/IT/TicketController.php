<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\NewTicketNotification;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets1 = Ticket::whereNotIn('status', ['Pending', 'Denied'])
        ->where('responsible_department', 'HIMS')
        ->orderByRaw("CAST(SUBSTRING(ticket_number, 8) AS UNSIGNED) DESC")
        ->get();
    
    $tickets2 = Ticket::where('created_by', auth()->id())
        ->orderByRaw("CAST(SUBSTRING(ticket_number, 8) AS UNSIGNED) DESC")
        ->get();
    
    // Merge both queries' results
    $tickets = $tickets1->concat($tickets2);
    
        $data = [];
    
        foreach ($tickets as $ticket) {
    
            $btnAccept = ($ticket->status !== 'Approved By Admin' || $ticket->created_by == auth()->id())
    ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Accept Disabled" disabled>
       <i class="fas fa-lg fa-fw fa-check-circle"></i>
    </button>'
    : '<button class="btn btn-xs btn-default text-success mx-1 shadow Accept" 
    title="Accept" data-id="' . $ticket->id . '">
   <i class="fas fa-lg fa-fw fa-check-circle"></i>
</button>';

    
$btnCompleted = ($ticket->status !== 'In Progress' || $ticket->created_by == auth()->id())
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
    
          $btnDelete = ($ticket->status !== 'In Progress' || $ticket->created_by == auth()->id())
    ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Delete Disabled" disabled>
        <i class="fas fa-lg fa-fw fa-times-circle"></i>
       </button>'
    : '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete"
    title="Defective" data-id="' . $ticket->id . '" data-toggle="modal" data-target="#deleteModal">
    <i class="fas fa-lg fa-fw fa-times-circle"></i>
</button>';

    
            $pdfDisplay = $ticket->image_url
                ? '<a href="' . asset($ticket->image_url) . '" target="_blank" class="btn btn-primary btn-sm">
                        View Ticket 
                   </a>' 
                : 'No PDF';
    
            $statusColors = [
                'Accepted' => 'badge-success',
                'Approved By Admin' => 'badge-success',
                'Denied' => 'badge-danger',
                'Completed' => 'badge-warning',
                'Defective' => 'badge-danger',
                'Pending' => 'badge-secondary',
                'In Progress' => 'badge-info'
            ];
    
            $rowData = [
                $ticket->ticket_number,
                $ticket->department,
                $ticket->responsible_department,
                $ticket->concern_type,
                $ticket->urgency,
                $pdfDisplay,
                '<span class="badge ' . ($statusColors[$ticket->status] ?? 'badge-secondary') . '">' . $ticket->status . '</span>',
                $ticket->approval_date ? \Carbon\Carbon::parse($ticket->approval_date)->format('m/d/Y') : null,
                $ticket->total_duration > 0 ? $ticket->total_duration . ' ' . Str::plural('day', $ticket->total_duration) : null,
                '<nobr>' . $btnAccept . $btnCompleted . $btnDelete . $btnShow . '</nobr>',
            ];
    
            $data[] = $rowData;
        }
    
        return view('IT.ticketing.index', compact('data'));
    }
    

    public function acceptTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'In Progress'; // Change status to "In Progress"
        $ticket->accepted_date = now(); // Store the approval date
        $ticket->save();
    
        return response()->json(['message' => 'Ticket is now In Progress!']);
    }


    public function complete(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'Completed';
        $ticket->completed_by = $request->completed_by; 
        $ticket->completed_date = now();

        // Calculate the difference in days from created_at to completed_date
        $completed_date = \Carbon\Carbon::now();
        $created_at = \Carbon\Carbon::parse($ticket->created_at);
        
        $total_duration = $created_at->diffInDays($completed_date);

        $ticket->total_duration = $total_duration;
        $ticket->save();
        
        return response()->json(['message' => 'Ticket marked as Completed!']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextId = Ticket::max('id') + 1;
        $ticketNumber = 'TICKET-' . $nextId;
        return view('IT.ticketing.create', compact('ticketNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Get next ticket number
    $nextId = Ticket::max('id') + 1;
    $ticketNumber = 'TICKET-' . $nextId;

    // Validate form inputs
    $validated = $request->validate([
        'department' => 'required',
        'responsible_department' => 'required',
        'concern_type' => 'required',
        'urgency' => 'required|string|in:Not Urgent,Neutral,Urgent',
        'serial_number' => 'required',
        'equipment' => 'nullable|string',
        'remarks' => 'nullable|string',
        'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Set default values
    $validated['ticket_number'] = $ticketNumber;
    $validated['status'] = 'Pending'; // Default status
    $validated['created_by'] = auth()->id(); // Assign logged-in user's ID

    // Handle Image Upload (store in public folder)
    if ($request->hasFile('image_url')) {
        $image = $request->file('image_url');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('ticket_images'), $imageName); // Save in public folder
        $validated['image_url'] = 'ticket_images/' . $imageName; // Save path for retrieval
    }

    // Save ticket to the database
    $ticket = Ticket::create($validated);

    if ($ticket) {
        $admins = User::role('Administrator')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewTicketNotification($ticket));
        }
        return redirect()->route('IT.ticketing.index')->with('success', 'Ticket created successfully.');
    } else {
        return back()->with('error', 'Failed to create ticket.');
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
        //
    }
    public function getTicketDetails($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        return response()->json($ticket);
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
    public function print($id)
{
    $ticket = Ticket::findOrFail($id);
    return view('staff.ticketing.print', compact('ticket'));
}

}

