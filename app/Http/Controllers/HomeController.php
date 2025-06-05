<?php

namespace App\Http\Controllers;

use App\Models\ScheduleList;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\PR;
use App\Models\PurchaserPO;
use App\Notifications\EventCreatedNotification;
use Illuminate\Support\Facades\Notification;
use App\Jobs\SendEventCreatedNotification;

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
    
        // Fetch the counts of Users, Events, Tickets, Purchase Orders, and Purchase Requests
        $userCount = User::count();
        $eventCount = ScheduleList::count();
        $ticketCount = Ticket::count();
        $purchaseOrderCount = PurchaserPO::count();
        $purchaseRequestCount = PR::count();
    
        return view('admin.dashboard', compact(
            'userCount', 'eventCount', 'ticketCount', 'purchaseOrderCount', 'purchaseRequestCount'
        ));
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
                'department'  => $event->from_department,
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
            'fromDepartment'   => 'required|string',
            'fromDate'         => 'required|date',
            'toDate'           => 'required|date|after_or_equal:fromDate',
        ]);
    
        $fromDate = Carbon::parse($validated['fromDate']);
        $toDate = Carbon::parse($validated['toDate']);
    
        if ($toDate->format('H:i') === '00:00') {
            $toDate->subSecond();
        }
    
        $event = ScheduleList::create([
            'event'       => $validated['eventTitle'],
            'description' => $validated['eventDescription'],
            'from_department' => $validated['fromDepartment'],
            'from_date'   => $fromDate->toDateTimeString(),
            'to_date'     => $toDate->toDateTimeString(),
            'status'      => 'active',
            'user_id'     => auth()->id(),
        ]);
    
        // Dispatch the SendEventCreatedNotification job synchronously
        SendEventCreatedNotification::dispatchSync($event);
    
        return redirect()->route('admin.schedule.calendar')->with('success', 'Event saved and users notified successfully!');
    }
    

    public function update(Request $request, $event)
    {
        $request->validate([
            'eventTitle' => 'required|string',
            'eventDescription' => 'nullable|string',
            'fromDepartment' => 'required|string',
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
            'from_department' => $request->fromDepartment,
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
