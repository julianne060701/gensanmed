<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.ticketing.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ticketing.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ticket_number' => 'required',
            'department' => 'required',
            'responsible_department' => 'required',
            'concern_type' => 'required',
            'remarks' => 'nullable',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('document')) {
            $validatedData['document'] = $request->file('document')->store('documents', 'public');
        }

        Ticket::create($validatedData);

        return redirect()->route('admin.ticketing.index')->with('success', 'Ticket request submitted successfully.');
    
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
