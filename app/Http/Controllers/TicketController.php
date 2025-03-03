<?php

namespace App\Http\Controllers;

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
        $tickets = Ticket::orderBy('created_at', 'desc')->get();

        $data = [];
    
        foreach ($tickets as $ticket) {
            $btnAccept = '<button class="btn btn-xs btn-default text-success mx-1 shadow Accept" 
            title="Accept" data-id="' . $ticket->id . '">
           <i class="fas fa-lg fa-fw fa-check-circle"></i>
        </button>';
        // <i class="fas fa-lg fa-fw fa-thumbs-up"></i>
                        
        $btnShow = '<button class="btn btn-xs btn-default text-info mx-1 shadow view-ticket" 
        title="View" data-id="' . $ticket->id . '" data-toggle="modal" data-target="#ticketModal">
        <i class="fa fa-lg fa-fw fa-eye"></i>
    </button>';


            $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" 
                            title="Decline" data-delete="' . $ticket->id . '" 
                            data-toggle="modal" data-target="#deleteModal">
                            <i class="fa fa-lg fa-fw fa-times-circle"></i>
                        </button>';
    
            $pdfDisplay = $ticket->image_url 
                ? '<a href="' . asset($ticket->image_url) . '" target="_blank" class="btn btn-primary btn-sm">
                        View Ticket 
                   </a>' 
                : 'No PDF';
    
            $statusColors = [
                'Approved' => 'badge-success',
                'Denied' => 'badge-danger',
                'Completed' => 'badge-warning',
                'Defective' => 'badge-danger',
                'Pending' => 'badge-secondary'
            ];

  
            $rowData = [
                $ticket->ticket_number,
                $ticket->department,
                $ticket->responsible_department,
                $ticket->concern_type,
                $pdfDisplay,
                '<span class="badge ' . ($statusColors[$ticket->status] ?? 'badge-secondary') . '">' . $ticket->status . '</span>',
                $ticket->created_at->format('m/d/Y'),
              '<nobr>' . $btnAccept .  $btnDelete . $btnShow  .  '</nobr>',
            ];
            $data[] = $rowData;
        }
        return view('admin.ticketing.index', compact('data'));
    }
public function accept($id)
{
    $ticket = Ticket::findOrFail($id);
    $ticket->status = 'approved';
    $ticket->save();

    return response()->json(['message' => 'Ticket approved successfully!']);
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextId = Ticket::max('id') + 1;
        $ticketNumber = 'TICKET-' . $nextId;
        return view('admin.ticketing.create' , compact('ticketNumber'));
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
        'urgency' => 'required|integer|min:1|max:5',
        'serial_number' => 'required',
        'remarks' => 'nullable|string',
        'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Set default values
    $validated['ticket_number'] = $ticketNumber;
    $validated['status'] = 'Pending'; // Default status

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
        return redirect()->route('admin.ticketing.index')->with('success', 'Ticket request submitted successfully.');
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
        $ticket = Ticket::findOrFail($id);
        return view('admin.ticketing.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            'serial_number' => $request->serial_number,
            'department' => $request->department,
            'responsible_department' => $request->responsible_department,
            'concern_type' => $request->concern_type,
            'urgency' => $request->urgency,
            'remarks' => $request->remarks,
        ]);
    
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('tickets', 'public');
            $ticket->update(['image_url' => $imagePath]);
        }
    
        return redirect()->route('staff.ticketing.index')->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
