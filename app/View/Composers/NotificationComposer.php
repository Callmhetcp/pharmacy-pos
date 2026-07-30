<?php

namespace App\View\Composers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            $view->with([
                'notifications' => collect(),
                'unreadCount' => 0,
            ]);

            return;
        }

        $user = Auth::user();

        $notifications = Notification::with('medicine')
            ->where(function ($query) use ($user) {
                $query->whereNull('role')
                      ->orWhere('role', $user->role);
            })
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::where(function ($query) use ($user) {
                $query->whereNull('role')
                      ->orWhere('role', $user->role);
            })
            ->where('is_read', false)
            ->count();

        $view->with([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}