<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('target_role')) {
            $query->where('target_role', $request->target_role);
        }

        $notifications = $query->latest()->take(10)->get();

        return response()->json([
            'unreadCount' => $notifications->where('is_read', 0)->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        $notification->update([
            'is_read' => 1,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function readAll()
    {
        Notification::where('is_read', 0)
            ->update([
                'is_read' => 1,
            ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
