<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\UserSMS;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use App\Services\ClickSendSMSService;
use App\Models\SmsGroup;
use App\Models\User;
use App\Models\Group;
use App\Models\SmsGroupUser;

class SMSController extends Controller
{
    protected $smsService;
  

    // public function __construct(PhilSMSService $smsService)
    // {
    //     $this->smsService = $smsService;
    // }
    public function __construct(ClickSendSMSService $smsService)
    {
        $this->smsService = $smsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = SmsGroup::all(); // Get all groups
        $users = UserSMS::all(); // Fetch all users from users_sms table
        
    return view('admin.schedule.sms', compact('users', 'groups'));

    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = UserSMS::all(); // Fetch all users
        return view('admin.schedule.create_sms', compact('users'));
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
public function createGroup(Request $request)
{
    $request->validate([
        'name' => 'required|string|unique:sms_groups,name',
        'users' => 'required|array',
        'users.*' => 'exists:users_sms,id',
    ]);

    $group = SmsGroup::create(['name' => $request->name]);
    $group->users()->attach($request->users);

    return redirect()->route('admin.schedule.sms')->with('success', 'Group created successfully!');
}

public function sendSMSToGroup(Request $request)
{
    $request->validate([
        'message' => 'required|string',
        'group_id' => 'required|exists:sms_groups,id',
    ]);

    $group = SmsGroup::findOrFail($request->group_id);
    $recipients = $group->users->pluck('phone')->toArray();

    $response = $this->smsService->sendSMS($recipients, $request->message);

    return response()->json($response);
}
public function bulkSMS()
{
    $groups = SmsGroup::all();  // Get all SMS groups
    $users = User::with('group')  // Get users with their groups
                ->get();

    return view('admin.bulk_sms', compact('groups', 'users'));
}
public function getRecipients(Request $request)
{
    $groupId = $request->input('group_id');

    if (!$groupId) {
        return response()->json([]);
    }

    // Fetch users in the selected group
    $users = SmsGroupUser::where('group_id', $groupId)
                ->with('user') // Ensure the User model is loaded
                ->get()
                ->map(function ($groupUser) {
                    return [
                        'id' => $groupUser->user->id,
                        'name' => $groupUser->user->name,
                        'phone' => $groupUser->user->phone
                    ];
                });

    return response()->json($users);
}


}
