<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::where('status', '!=', 'Pending')
        ->where('responsible_department', 'Engineer')
        ->orderByRaw("CAST(SUBSTRING(ticket_number, 8) AS UNSIGNED) DESC")
        ->get();


        $data = [];
    
        foreach ($tickets as $ticket) {
            
            $btnAccept = ($ticket->status !== 'Approved By Admin')
            ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Accept Disabled" disabled>
               <i class="fas fa-lg fa-fw fa-check-circle"></i>
            </button>'
            : '<button class="btn btn-xs btn-default text-success mx-1 shadow Accept" 
            title="Accept" data-id="' . $ticket->id . '">
           <i class="fas fa-lg fa-fw fa-check-circle"></i>
        </button>';

        $btnCompleted = ($ticket->status !== 'In Progress')
        ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Complete Disabled" disabled>
            <i class="fas fa-lg fa-fw fa-thumbs-up"></i>
           </button>'
        : '<button class="btn btn-xs btn-default text-success mx-1 shadow complete-ticket" 
        title="Completed" data-id="' . $ticket->id . '">
        <i class="fas fa-lg fa-fw fa-thumbs-up"></i>
        </button>';
    
                        
        $btnShow = '<button class="btn btn-xs btn-default text-info mx-1 shadow view-ticket" 
        title="View" data-id="' . $ticket->id . '" data-toggle="modal" data-target="#ticketModal">
        <i class="fa fa-lg fa-fw fa-eye"></i>
    </button>';


    $btnDelete = ($ticket->status !== 'In Progress')
        ? '<button class="btn btn-xs btn-default text-muted mx-1 shadow" title="Complete Disabled" disabled>
            <i class="fas fa-lg fa-fw fa-times-circle"></i>
           </button>'
        :'<button class="btn btn-xs btn-default text-danger mx-1 shadow Delete"
    title="Defective" data-id="' . $ticket->id . '" data-toggle="modal" data-target="#deleteModal">
    <i class="fas fa-lg fa-fw fa-times-circle"></i>
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
                $ticket->approval_date ? \Carbon\Carbon::parse($ticket->approval_date)->format('m/d/Y') : null,
                $ticket->total_duration > 0 ? $ticket->total_duration . ' ' . Str::plural('day', $ticket->total_duration) : null,
               
              '<nobr>' . $btnAccept . $btnCompleted .  $btnDelete . $btnShow  .  '</nobr>',
            ];
            $data[] = $rowData;
        }
        return view('engineer.pms.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('engineer.pms.create');
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
