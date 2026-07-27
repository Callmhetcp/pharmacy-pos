<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

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

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

   public function destroy(User $user)
    {
        if (auth()->user()->is($user)) {

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
    if (auth()->user()->is($user)) {

        return back()->with(
            'error',
            'You cannot deactivate your own account.'
        );

    }

    $user->status = $user->status == 'active'
        ? 'inactive'
        : 'active';

    $user->save();

    return back()->with(
        'success',
        'User status updated successfully.'
    );
}
}