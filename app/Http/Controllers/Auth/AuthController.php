<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended('/')->with('info', 'Anda login sebagai Member.');
        }

        return back()->withErrors([
            'email' => 'Kombinasi email atau password tidak sesuai.',
        ])->onlyInput('email');
    }

    public function quickLogin(Request $request): RedirectResponse
    {
        $role = $request->input('role', 'admin');
        $user = User::where('role', $role)->first();

        if (! $user) {
            return back()->with('error', "User dengan role {$role} tidak ditemukan.");
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', "Berhasil login sebagai {$user->name} (Admin).");
        }

        return redirect('/')->with('info', "Berhasil login sebagai {$user->name} (Member).");
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah keluar dari sistem.');
    }
}
