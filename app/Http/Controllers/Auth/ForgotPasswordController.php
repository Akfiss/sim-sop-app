<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan Form Input Email
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Proses Kirim Link ke Email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Laravel otomatis mengirim link reset ke email
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(['status' => __($status)]);
        }

        return back()->withErrors(['email' => __($status)]);
    }

    // 3. Tampilkan Form Reset Password Baru
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // 4. Proses Simpan Password Baru
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8', // password_confirmation wajib ada di form
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password) // Hash password baru
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // Jika sukses, redirect ke login dengan pesan sukses
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}
