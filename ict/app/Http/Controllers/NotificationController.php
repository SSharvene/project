<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Return a JSON list of unread notifications (simple example).
     */
    public function fetch(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([], 401);
        }

        // example: assuming you use built-in notifications
        $notifications = $user->unreadNotifications()->limit(20)->get();

        // Return minimal payload
        return response()->json([
            'data' => $notifications,
        ]);
    }

    /**
     * Mark all notifications as read for the current user.
     */
    public function markAllRead(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read']);
    }
}
