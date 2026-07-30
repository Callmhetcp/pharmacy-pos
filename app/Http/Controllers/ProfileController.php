<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;
use Illuminate\View\View;
use App\Helpers\NotificationHelper;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the user's profile information.
     */
     public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('avatar')) {

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {

                Storage::disk('public')->delete($user->avatar);

            }

            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Profile Updated',
            'module' => 'Profile',
            'description' => 'Updated own profile',
            'ip_address' => $request->ip(),
            'browser' => $request->userAgent(),
        ]);
        NotificationHelper::create(
            title: 'Profile Updated',
            message: $user->name . ' updated their profile.',
            type: 'info',
            role: 'admin',
            userId: $user->id
        );

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required', 'current_password'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $user = Auth::user();

    $user->update([
        'password' => Hash::make($request->password),
    ]);

    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'Password Changed',
        'module' => 'Profile',
        'description' => 'Changed own password',
        'ip_address' => $request->ip(),
        'browser' => $request->userAgent(),
    ]);
    NotificationHelper::create(
    title: 'Password Changed',
    message: $user->name . ' changed their password.',
    type: 'warning',
    role: 'admin',
    userId: $user->id
);

    return back()->with('success', 'Password changed successfully.');
}


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
