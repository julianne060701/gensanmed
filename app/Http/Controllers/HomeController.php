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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->fetchEvents();
        }

        return view('admin.home');
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
            'toDate'           => 'required|date',
        ]);

        ScheduleList::create([
            'event'       => $validated['eventTitle'],
            'description' => $validated['eventDescription'],
            'from_date'   => Carbon::parse($validated['fromDate'])->toDateTimeString(),
            'to_date'     => Carbon::parse($validated['toDate'])->toDateTimeString(),
            'status'      => 'active',
            'user_id'     => auth()->id(),
        ]);

        return redirect()->route('admin.home')->with('success', 'Event saved successfully!');
    }

    public function update(Request $request, $event)
    {
        $request->validate([
            'eventTitle' => 'required|string',
            'fromDate'   => 'required|date',
            'toDate'     => 'required|date',
        ]);

        $event = ScheduleList::findOrFail($event);
        $event->update([
            'event'       => $request->eventTitle,
            'description' => $request->eventDescription,
            'from_date'   => Carbon::parse($request->fromDate)->toDateTimeString(),
            'to_date'     => Carbon::parse($request->toDate)->toDateTimeString(),
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
