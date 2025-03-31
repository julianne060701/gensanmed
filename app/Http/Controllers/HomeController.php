<?php

namespace App\Http\Controllers;

use App\Models\ScheduleList;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home(Request $request)
    {
        if ($request->ajax()) {
            return $this->fetchEvents();
        }

        return view('admin.dashboard');
    }

    public function index()
    {
        return view('admin.schedule.calendar');
    }

    private function getEventColor()
    {
        $colors = ['#28a745', '#dc3545', '#ffc107', '#007bff', '#17a2b8', '#6f42c1'];
        return $colors[array_rand($colors)];
    }

    public function fetchEvents()
    {
        $events = ScheduleList::all();

        $formattedEvents = $events->map(function ($event) {
            return [
                'id'          => $event->id,
                'title'       => $event->event,
                'description' => $event->description,
                'start'       => Carbon::parse($event->from_date)->toIso8601String(),
                'end'         => Carbon::parse($event->to_date)->toIso8601String(),
                'color'       => $event->color ?? $this->getEventColor(),
            ];
        });

        return response()->json($formattedEvents);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'eventTitle'       => 'required|string',
            'eventDescription' => 'nullable|string',
            'fromDate'         => 'required|date',
            'toDate'           => 'required|date|after_or_equal:fromDate',
        ]);
    
        $fromDate = Carbon::parse($validated['fromDate']);
        $toDate = Carbon::parse($validated['toDate']);
    
        // Adjust the to_date to prevent overlap into the next day
        if ($toDate->format('H:i') === '00:00') {
            $toDate->subSecond(); // Move it back by 1 second to stay within the intended date
        }
    
        ScheduleList::create([
            'event'       => $validated['eventTitle'],
            'description' => $validated['eventDescription'],
            'from_date'   => $fromDate->toDateTimeString(),
            'to_date'     => $toDate->toDateTimeString(),
            'status'      => 'active',
            'user_id'     => auth()->id(),
        ]);
    
        return redirect()->route('admin.schedule.calendar')->with('success', 'Event saved successfully!');
    }

    public function update(Request $request, $event)
    {
        $request->validate([
            'eventTitle' => 'required|string',
            'fromDate'   => 'required|date',
            'toDate'     => 'required|date|after_or_equal:fromDate',
        ]);
    
        $event = ScheduleList::findOrFail($event);
        
        $fromDate = Carbon::parse($request->fromDate);
        $toDate = Carbon::parse($request->toDate);
    
        // Adjust to_date if it's exactly midnight
        if ($toDate->format('H:i') === '00:00') {
            $toDate->subSecond();
        }
    
        $event->update([
            'event'       => $request->eventTitle,
            'description' => $request->eventDescription,
            'from_date'   => $fromDate->toDateTimeString(),
            'to_date'     => $toDate->toDateTimeString(),
        ]);
    
        return response()->json(['message' => 'Event updated successfully']);
    }

    public function destroy($event)
    {
        $event = ScheduleList::findOrFail($event);
        $event->update(['status' => 'inactive']);
        $event->delete();

        return response()->json(['message' => 'Event deleted/inactivated successfully']);
    }
}
