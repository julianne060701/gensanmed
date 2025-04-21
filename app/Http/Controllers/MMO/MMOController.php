<?php

namespace App\Http\Controllers\MMO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScheduleList;
use App\Models\User;
use App\Models\Ticket;
class MMOController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->user()->id;

        $eventCount = ScheduleList::count();
        $totalTicketCount = Ticket::where('created_by', $userId)->count();
        $requestTicketCount = Ticket::where('created_by', $userId)
                                    ->where('status', 'Pending')
                                    ->count();
        return view('mmo.dashboard', compact('eventCount', 'totalTicketCount', 'requestTicketCount'));

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
