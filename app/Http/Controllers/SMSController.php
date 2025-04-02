<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\UserSMS;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use App\Services\ClickSendSMSService;

class SMSController extends Controller
{
    protected $smsService;
    public function __construct(ClickSendSMSService $smsService)
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

//    public function sendSMS(Request $request)
// {
//     $request->validate([
//         'message' => 'required|string',
//         'recipients' => 'required|array',
//     ]);

//     $sid = env('TWILIO_SID');
//     $token = env('TWILIO_AUTH_TOKEN');
//     $from = env('TWILIO_PHONE');
//     $client = new Client($sid, $token);

//     $messageText = $request->message;
//     $recipients = $request->recipients;

//     foreach ($recipients as $phone) {
//         try {
//             // Ensure phone number starts with "+"
//             if (substr($phone, 0, 1) !== "+") {
//                 throw new \Exception("Invalid phone number format: $phone");
//             }

//             $message = $client->messages->create($phone, [
//                 'from' => $from,
//                 'body' => $messageText
//             ]);

//             Log::info("SMS sent successfully to $phone. SID: " . $message->sid);

//         } catch (\Exception $e) {
//             Log::error("Failed to send SMS to $phone: " . $e->getMessage());
//             return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
//         }
//     }

//     return response()->json(['success' => true, 'message' => 'SMS sent successfully!']);
// }

public function sendSMS(Request $request)
{
    $request->validate([
        'message' => 'required|string',
        'recipients' => 'required|array',
        'recipients.*' => 'required|string', // Ensure each recipient is valid
    ]);

    $smsService = new \App\Services\ClickSendSMSService();
    $response = $smsService->sendSMS($request->recipients, $request->message);

    return response()->json($response);
}
}
