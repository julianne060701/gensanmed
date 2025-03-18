<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index', [
            'notifications' => Auth::user()->notifications
        ]);
    }

    public function fetch()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications->count(),
            'notifications' => Auth::user()->unreadNotifications
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return back();
    }
}

