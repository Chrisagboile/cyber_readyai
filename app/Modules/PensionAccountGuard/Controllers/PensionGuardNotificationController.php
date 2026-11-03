<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class PensionGuardNotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('pension_guard');

        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        $unreadCount = $user->unreadNotifications()->count();

        return view('pension-guard.notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markAsRead(Request $request, string $notification)
    {
        $user = $request->user('pension_guard');

        $record = $user->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        if ($record->read_at === null) {
            $record->markAsRead();
        }

        return redirect()
            ->route('pension-guard.notifications.index')
            ->with('success', 'Notification marked as read.');
    }
}
