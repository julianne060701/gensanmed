<?php

namespace App\Http\Controllers;

use App\Models\ScheduleList; // Import the ScheduleList model
use Illuminate\Http\Request;

class EventsController extends Controller
{
    public function store(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'eventTitle' => 'required|string',
            'eventDescription' => 'nullable|string',
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
        ]);

        // Create and save the event
        $event = new ScheduleList();
        $event->event = $validated['eventTitle'];
        $event->description = $validated['eventDescription'];
        $event->from_date = $validated['fromDate'];
        $event->to_date = $validated['toDate'];
        $event->status = 'active'; // or any other default status
        $event->user_id = auth()->id(); // Store the authenticated user's ID
        $event->save();

        // Return a success message
        return response()->json(['message' => 'Event saved successfully!']);
    }
}
