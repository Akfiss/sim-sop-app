<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    /**
     * LOGIKA PENGARAHAN LALU LINTAS
     * Berjalan otomatis setelah user sukses memasukkan username & password.
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        // Tentukan tujuan berdasarkan role
        $url = match($user->role) {
            'SUPER ADMIN' => '/admin',       // Arahkan ke URL panel admin
            'PENGUSUL'    => '/pengusul',    // Arahkan ke URL panel pengusul
            'VERIFIKATOR' => '/verifikator', // Arahkan ke URL panel verifikator
            'DIREKSI'     => '/direksi',     // Arahkan ke URL panel direksi
            default       => '/',            // Default jika role aneh
        };

        return redirect()->intended($url);
    }
}
