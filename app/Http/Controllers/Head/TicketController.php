<?php

namespace App\Http\Controllers\Head;

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
        $tickets = Ticket::where('created_by', auth()->id())
        ->orderByRaw("CAST(SUBSTRING(ticket_number, 8) AS UNSIGNED) DESC")
        ->get();

        $data = [];
    
        foreach ($tickets as $ticket) {
            $isDisabled = ($ticket->status === 'Approved By Admin') ? 'disabled' : '';

    $btnEdit = ($ticket->status === 'Approved By Admin'|| 'Completed')
        ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Edit Disabled" disabled>
                <i class="fa fa-lg fa-fw fa-pen"></i>
           </button>'

        : '<a href="' . route('head.ticketing.edit', $ticket->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                <i class="fa fa-lg fa-fw fa-pen"></i>
           </a>';
            $btnDelete = ($ticket->status === 'Approved By Admin' || 'Completed' || 'Pending')
                ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Delete Disabled" disabled>
                        <i class="fa fa-lg fa-fw fa-trash"></i>
                   </button>'
                : '<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete" 
                            title="Delete" data-delete="' . $ticket->id . '" 
                            data-toggle="modal" data-target="#deleteModal">
                            <i class="fa fa-lg fa-fw fa-trash"></i>
                        </button>';

                        $btnShow = '<button class="btn btn-xs btn-default text-info mx-1 shadow view-ticket" 
                        title="View" data-id="' . $ticket->id . '" data-toggle="modal" data-target="#ticketModal">
                        <i class="fa fa-lg fa-fw fa-eye"></i>
                        </button>';

                        $btnPrint = '<button class="btn btn-xs btn-default text-secondary mx-1 shadow print-ticket" 
                        title="Print" data-id="' . $ticket->id . '">
                        <i class="fa fa-lg fa-fw fa-print"></i>
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
                $ticket->created_at->format('m/d/Y'),
                $ticket->total_duration > 0 ? $ticket->total_duration . ' ' . Str::plural('day', $ticket->total_duration) : null,
               '<nobr>' . $btnShow . $btnEdit . $btnDelete . $btnPrint . '</nobr>',
            ];
            $data[] = $rowData;
        }

        return view('head.ticketing.index', compact('data'));
    }

    public function print($id)
    {
        $ticket = Ticket::findOrFail($id); // Fetch ticket details
        return view('head.ticketing.print', compact('ticket')); // Load print view
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
        return view('head.ticketing.create', compact('ticketNumber'));
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
        // Notify administrators
        $admins = User::role('Administrator')->get();
        
        // Notify users whose role matches the responsible_department
        $departmentUsers = User::whereHas("roles", function($q) use ($ticket) {
            $q->where("name", $ticket->responsible_department); // e.g. "Engineer" or "IT"
        })->get();

        // Combine both collections and remove duplicates (in case admin has both roles)
        $usersToNotify = $admins->merge($departmentUsers)->unique('id');

        foreach ($usersToNotify as $user) {
            $user->notify(new NewTicketNotification($ticket));
        }

        return redirect()->route('staff.ticketing.index')->with('success', 'Ticket created successfully.');
    } else {
        return back()->with('error', 'Failed to create ticket.');
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::find($id);
        return response()->json($ticket);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       $ticket = Ticket::findOrFail($id);
        return view('head.ticketing.edit', compact('ticket'));
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
        'equipment' => 'nullable|string',
        'urgency' => 'required|string|in:Not Urgent,Neutral,Urgent',
        'serial_number' => 'required',
        'remarks' => 'nullable|string',
        'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|in:Pending,Approved,Denied,Completed,Defective', 
        ]);

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('ticket_images', $imageName, 'public');
            $validated['image_url'] = 'storage/' . $imagePath;
        }

        $ticket->update($validated);
        return redirect()->route('head.ticketing.index')->with('success', 'Ticket updated successfully!');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $ticket = Ticket::findOrFail($id);
       $ticket->delete();

       return redirect()->route('head.ticketing.index')->with('success', 'Ticket deleted successfully!');
    }
}
