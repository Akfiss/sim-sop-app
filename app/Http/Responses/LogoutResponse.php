<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * LOGIKA SETELAH LOGOUT
     * Mengarahkan user ke rute 'login' (Halaman Login Netral)
     */
    public function toResponse($request): RedirectResponse
    {
        // Arahkan ke rute bernama 'login' yang sudah kita buat di routes/web.php
        return redirect()->route('login');
    }
}
