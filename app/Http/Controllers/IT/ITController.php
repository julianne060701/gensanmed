<?php

namespace App\Http\Controllers\IT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class ITController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
      // Fetch and count tickets for 'HIMS' department based on status
      $newTicketCount = Ticket::where('responsible_department', 'HIMS')
      ->where('status', 'Approved By Admin')
      ->count();

    $completedTicketCount = Ticket::where('responsible_department', 'HIMS')
        ->where('status', 'Completed')
        ->count();

    $totalTicketCount = Ticket::where('responsible_department', 'HIMS')->count();

    $defectiveTicketCount = Ticket::where('responsible_department', 'HIMS')
        ->where('status', 'Defective')
        ->count();
 
         // Return the view with the counts
         return view('IT.home', compact(
             'newTicketCount', 'completedTicketCount', 'totalTicketCount', 'defectiveTicketCount'));
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
