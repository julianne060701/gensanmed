<?php

namespace App\Http\Controllers\Staff;

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
        $tickets = Ticket::where('created_by', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

        $data = [];
    
        foreach ($tickets as $ticket) {
            $isDisabled = ($ticket->status === 'Approved') ? 'disabled' : '';

    $btnEdit = ($ticket->status === 'Approved')
        ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Edit Disabled" disabled>
                <i class="fa fa-lg fa-fw fa-pen"></i>
           </button>'
        : '<a href="' . route('staff.ticketing.edit', $ticket->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
           </a>';
            $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" 
                            title="Delete" data-delete="' . $ticket->id . '" 
                            data-toggle="modal" data-target="#deleteModal">
                            <i class="fa fa-lg fa-fw fa-trash"></i>
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
               '<nobr>'  . $btnEdit . $btnDelete . '</nobr>',
            ];
            $data[] = $rowData;
        }

        return view('staff.ticketing.index', compact('data'));
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
    return view('staff.ticketing.create', compact('ticketNumber'));
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
        return redirect()->route('staff.ticketing.index')->with('success', 'Ticket created successfully.');
    } else {
        return back()->with('error', 'Failed to create ticket.');
    }
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('staff.ticketing.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $ticket = Ticket::findOrFail($id);

    $validated = $request->validate([
        'ticket_number' => 'required|unique:tickets,ticket_number,' . $id,
        'department' => 'required',
        'responsible_department' => 'required',
        'concern_type' => 'required',
        'urgency' => 'required|integer|min:1|max:5',
        'serial_number' => 'required',
        'remarks' => 'nullable|string',
        'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|in:Pending,Approved,Denied,Completed,Defective',
    ]);

    // Handle Image Upload
    if ($request->hasFile('image_url')) {
        $image = $request->file('image_url');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = $image->storeAs('ticket_images', $imageName, 'public');
        $validated['image_url'] = 'storage/' . $imagePath;
    }

    $ticket->update($validated);

    return redirect()->route('staff.ticketing.index')->with('success', 'Ticket updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('staff.ticketing.index')->with('success', 'Ticket deleted successfully.');
    }
}
