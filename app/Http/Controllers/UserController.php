<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\NotificationHelper;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

       $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        NotificationHelper::create(
            title: 'New User',
            message: $user->name . ' has been added to the system.',
            type: 'info',
            role: 'admin'
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        NotificationHelper::create(
            title: 'User Updated',
            message: $user->name . "'s account information was updated.",
            type: 'info',
            role: 'admin',
            userId: $user->id
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

   public function destroy(User $user)
{
    if (Auth::id() === $user->id) {

        return back()->with(
            'error',
            'You cannot deactivate your own account.'
        );
    }

    $user->status = 'inactive';
    $user->save();

    return back()->with(
        'success',
        'User has been deactivated successfully.'
    );
}

    public function toggleStatus(User $user)
{
   if (Auth::id() === $user->id) {

    return back()->with(
        'error',
        'You cannot deactivate your own account.'
    );
}
    $user->status = $user->status == 'active'
        ? 'inactive'
        : 'active';

    $user->save();

    NotificationHelper::create(
    title: 'User Status Changed',
    message: $user->name . ' has been ' .
        ($user->status === 'active' ? 'activated' : 'deactivated') . '.',
    type: 'warning',
    role: 'admin',
    userId: $user->id
);

    return back()->with(
        'success',
        'User status updated successfully.'
    );
}

public function resetPassword(Request $request, User $user)
{
    $request->validate([
        'password' => [
            'required',
            'confirmed',
            'min:8',
        ]
    ]);

    $user->update([
        'password' => Hash::make($request->password)
    ]);

    ActivityLog::create([
        'user_id'     => Auth::id(),
        'action'      => 'Password Reset',
        'module'      => 'User Management',
        'description' => 'Reset password for user: ' . $user->name,
        'ip_address'  => $request->ip(),
        'browser'     => $request->userAgent(),
    ]);
    NotificationHelper::create(
    title: 'Password Reset',
    message: 'Password was reset for ' . $user->name . '.',
    type: 'warning',
    role: 'admin',
    userId: $user->id
);

    return back()->with('success', 'Password reset successfully.');
}
}