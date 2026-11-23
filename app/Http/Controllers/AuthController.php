<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Cek apakah user ada dan aktif
        $user = User::where('email', $request->email)->first();

        if ($user && !$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun tidak aktif. Hubungi admin.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();


            // Update last login
            auth()->user()->update(['last_login_at' => now()]);

            // Redirect based on user role
            $user = auth()->user();
            if ($user->isAdmin()) {
                return redirect()->route('dashboard');
            }

            if ($user->isPengurus()) {
                return redirect()->route('dashboard');
            }

            return redirect()->route('dashboard');


            // Default fallback
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
