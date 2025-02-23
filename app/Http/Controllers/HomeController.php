<?php

namespace App\Http\Controllers;
use App\Models\ScheduleList;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Check if the request is AJAX (for fetching events)
        if ($request->ajax()) {
            $events = ScheduleList::all();
    
            $eventData = $events->map(function ($event) {
                return [
                    'title' => $event->title,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'color' => $this->getEventColor(), // Assign a random color
                ];
            });
    
            return response()->json($eventData);
        }
    
        // If it's NOT an AJAX request, return the view
        return view('admin.home');
    }
    
    private function getEventColor()
    {
        $colors = ['#28a745', '#dc3545', '#ffc107', '#007bff', '#17a2b8', '#6f42c1'];
        return $colors[array_rand($colors)]; // Randomly select a color
    }
    
    public function fetchEvents()
    {
        $events = ScheduleList::all();
    
        $formattedEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->event,
                'description' => $event->description,
                'start' => $event->from_date,
                'end' => $event->to_date,
                'color' => $event->color ?? $this->getEventColor(), // Use event color if available, otherwise assign a random color
            ];
        });
    
        return response()->json($formattedEvents);
    }
    
   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'eventTitle' => 'required|string',
            'eventDescription' => 'nullable|string',
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
        ]);

        $event = ScheduleList::create([
            'event' => $validated['eventTitle'],
            'description' => $validated['eventDescription'],
            'from_date' => $validated['fromDate'],
            'to_date' => $validated['toDate'],
            'status' => 'active',
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Event saved successfully!']);
    }
    public function update(Request $request, $event)
    {
        $request->validate([
            'eventTitle' => 'required|string',
            'fromDate' => 'required|date',
            'toDate' => 'required|date',
        ]);

        $event = ScheduleList::findOrFail($event);
        $event->update([
            'event' => $request->eventTitle,
            'description' => $request->eventDescription,
            'from_date' => $request->fromDate,
            'to_date' => $request->toDate,
        ]);

        return response()->json(['message' => 'Event updated successfully']);
    }

    public function destroy($event)
{
    // Find event by ID
    $event = ScheduleList::findOrFail($event);

    // Optionally, update the status to 'inactive' instead of deleting
    $event->update(['status' => 'inactive']);

    // Or if you want to actually delete it:
     $event->delete();

    return response()->json(['message' => 'Event deleted/inactivated successfully']);
}
}
