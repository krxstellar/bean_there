<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminNotificationsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifications = $user->notifications()
            ->orderByRaw('read_at IS NULL DESC')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.notifications', compact('notifications'));
    }

    public function markAllRead(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function markRead(Request $request, $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->delete();
            return redirect()->back()->with('success', 'Notification removed.');
        }

        return redirect()->back()->with('error', 'Notification not found.');
    }

    public function viewAndRedirect(Request $request, $id)
    {
        $user = $request->user();
        $notification = $user->notifications()->where('id', $id)->first();
        if (! $notification) {
            return redirect()->back()->with('error', 'Notification not found.');
        }

        $data = $notification->data;
        $orderId = $data['order_id'] ?? null;

        $notification->delete();

        if ($orderId) {
            return redirect()->route('admin.orders.show', ['order' => $orderId]);
        }

        return redirect()->back()->with('success', 'Notification removed.');
    }
}
