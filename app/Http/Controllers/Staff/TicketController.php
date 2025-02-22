<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Models\Ticket;
class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::all();
        return view('staff.ticketing.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.ticketing.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|unique:tickets,ticket_number',
            'department' => 'required',
            'responsible_department' => 'required',
            'concern_type' => 'required',
            'urgency' => 'required|integer|min:1|max:5',
            'serial_number' => 'required',
            'remarks' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:Pending,Approved,Denied,Send to Supplier',
        ]);

        $data = $request->all();

        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('ticket_images', 'public');
            $data['image_url'] = $imagePath;
        }

        Ticket::create($data);

        return redirect()->route('staff.ticketing.index')->with('success', 'Ticket created successfully.');
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
