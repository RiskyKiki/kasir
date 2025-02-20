<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Ambil user yang sedang login
            $request->session()->regenerate();

            // Catat aktivitas login
            activity()
                ->causedBy($user)
                ->withProperties(['email' => $user->email])
                ->log('User berhasil login');

            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function logout(Request $request)
    {
        // Ambil user sebelum logout (karena setelah logout, Auth::user() sudah null)
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Catat aktivitas logout jika user tersedia
        if ($user) {
            activity()
                ->causedBy($user)
                ->withProperties(['email' => $user->email])
                ->log('User berhasil logout');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah logout!');
    }
}
