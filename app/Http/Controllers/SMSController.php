<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\UserSMS;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use App\Services\ClickSendSMSService;
use App\Services\PhilSMSService;

class SMSController extends Controller
{
    protected $smsService;
  

    public function __construct(PhilSMSService $smsService)
    {
        $this->smsService = $smsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = UserSMS::all(); // Fetch all users from users_sms table
    return view('admin.schedule.sms', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.schedule.create_sms');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users_sms,phone|max:15',
        ]);

        UserSMS::create([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.schedule.sms')->with('success', 'User added successfully!');
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



public function sendSMS(Request $request)
{
    $request->validate([
        'message' => 'required|string',
        'recipients' => 'required|array',
        'recipients.*' => 'required|string',
    ]);

    $response = $this->smsService->sendSMS($request->recipients, $request->message);

    return response()->json($response);
}
}
