<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => ['required'], // Sesuaikan dengan kolom login Anda (username/email)
            'password' => ['required'],
        ]);

        // Coba Login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // --- LOGIKA REDIRECT (Sama seperti LoginResponse) ---
            $user = Auth::user();

            $url = match($user->role) {
                'SUPER ADMIN' => '/admin',
                'PENGUSUL'    => '/pengusul',
                'VERIFIKATOR' => '/verifikator',
                'DIREKSI'     => '/direksi',
                default       => '/',
            };

            return redirect()->intended($url);
        }

        // Jika Gagal
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    // 3. Logout (Opsional, untuk tombol keluar di landing page)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
