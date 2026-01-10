<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.auth');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($request->only('username', 'password'))) {
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        // 🔑 REDIRECT SESUAI LAB
        $lab = auth()->user()->lab;

        if ($lab === 'iot') {
            return redirect('/dashboard/iot');
        }

        if ($lab === 'jaringan') {
            return redirect('/dashboard/jaringan');
        }

        if ($lab === 'cloud') {
            return redirect('/dashboard/cloud');
        }

        // fallback
        return redirect('/auth');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth');
    }
}
