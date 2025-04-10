<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    public function show()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function get()
    {
        $notifications = Auth::user()->unreadNotifications->take(5); // Only latest 5
        $data = [];
    
        foreach ($notifications as $notification) {
            $data[] = [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'Notification',
                'message' => $notification->data['message'] ?? '',
                'url' => $notification->data['url'] ?? '#',
                'time' => $notification->created_at->diffForHumans(),
            ];
        }
    
        return response()->json([
            'label' => auth()->user()->unreadNotifications->count(),
        ]);
    }
    public function markAllRead()
    {
        // Mark all notifications as read for the authenticated user
        auth()->user()->notifications->markAsRead();
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->route('admin.ticketing.index');
    }
    
}

