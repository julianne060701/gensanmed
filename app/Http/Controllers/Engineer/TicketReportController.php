<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TicketReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::whereIn('status', ['Completed', 'Defective'])
        ->where('responsible_department', 'Engineer') 
        ->orderBy('ticket_number', 'desc')
        ->get();

        $data = [];
    
        foreach ($tickets as $ticket) {
            
    

    
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
                $ticket->equipment,
                $ticket->serial_number,
                $ticket->remarks,
                $ticket->urgency,
                '<span class="badge ' . ($statusColors[$ticket->status] ?? 'badge-secondary') . '">' . $ticket->status . '</span>',
                Carbon::parse($ticket->created_at)->format('m/d/Y'),
                Carbon::parse($ticket->approval_date)->format('m/d/Y'),
                Carbon::parse($ticket->accepted_date)->format('m/d/Y'),
                $ticket->total_duration > 0 ? $ticket->total_duration . ' ' . Str::plural('day', $ticket->total_duration) : null ,
                $ticket->remarks_by,
                $ticket-> responsible_remarks,
                $ticket-> completed_by,
            ];
            $data[] = $rowData;
        }
        return view ('engineer.reports.ticketing_report', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
