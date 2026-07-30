<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function index(Request $request)
{
    $search = $request->search;

    $notifications = Notification::query()

        ->when($search, function ($query) use ($search) {

            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");

        })

        ->latest()

        ->paginate(15)

        ->withQueryString();

    return view('notifications.index', compact(
        'notifications',
        'search'
    ));
}
    public function markAsRead(Notification $notification)
    {
        $notification->update([
            'is_read' => true
        ]);

        return back();
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)
            ->update([
                'is_read' => true
            ]);

        return back();
    }

public function latest()
{
    $user = Auth::user();

    $notifications = Notification::with('medicine')
        ->where(function ($query) use ($user) {
            $query->whereNull('role')
                  ->orWhere('role', $user->role);
        })
        ->latest()
        ->take(10)
        ->get();

    $count = Notification::where(function ($query) use ($user) {
            $query->whereNull('role')
                  ->orWhere('role', $user->role);
        })
        ->where('is_read', false)
        ->count();

    return response()->json([
        'html' => view('notifications.list', compact('notifications'))->render(),
        'count' => $count,
    ]);
}
public function destroy(Notification $notification)
{
    $notification->delete();

    return back()->with(
        'success',
        'Notification deleted.'
    );
}

public function clearRead()
{
    Notification::where('is_read', true)->delete();

    return back()->with(
        'success',
        'Read notifications deleted.'
    );
}
public function clearAll()
{
    Notification::query()->delete();

    return back()->with(
        'success',
        'All notifications have been cleared.'
    );
}
}