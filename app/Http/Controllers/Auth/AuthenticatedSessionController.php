<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Helpers\ActivityHelper;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = Auth::user();


        ActivityHelper::log(
            'Login',
            'Authentication',
            'User logged in: ' . Auth::user()->name
        );

    return match ($user->role) {
        'admin' => redirect()->route('dashboard'),
        'cashier' => redirect()->route('cashier.dashboard'),
        'pharmacist' => redirect()->route('pharmacist.dashboard'),
        'storekeeper' => redirect()->route('storekeeper.dashboard'),
        default => redirect('/'),
    };
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
{

    if (Auth::check()) {

        ActivityHelper::log(
            'Logout',
            'Authentication',
            'User logged out: ' . Auth::user()->name
        );

    }


    Auth::guard('web')->logout();


    $request->session()->invalidate();


    $request->session()->regenerateToken();


    return redirect('/');

}
}
