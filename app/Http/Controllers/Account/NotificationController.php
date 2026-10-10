<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(20)
            ->through(fn ($n) => [
                'id' => $n->id,
                'type' => $n->data['type'] ?? 'notification',
                'title' => $n->data['title'] ?? 'Notification',
                'body' => $n->data['body'] ?? '',
                'action_url' => $n->data['action_url'] ?? null,
                'read_at' => $n->read_at?->format('M d, Y H:i'),
                'is_read' => $n->read_at !== null,
                'created_at' => $n->created_at->format('M d, Y H:i'),
            ]);

        return Inertia::render('account/notifications', [
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        return redirect()->back();
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }
}
