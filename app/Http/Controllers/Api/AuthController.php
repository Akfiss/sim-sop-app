<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cari User berdasarkan Username
        $user = User::where('username', $request->username)->first();

        // 3. Cek Password & Status User
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah.'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda dinonaktifkan. Hubungi admin.'
            ], 403);
        }

        // 4. Buat Token (Sanctum)
        // Kita hapus token lama agar tidak menumpuk (opsional, bagus untuk keamanan)
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Kirim Response JSON
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'user' => $user,
                'token' => $token,
                'role' => $user->role // Penting untuk frontend handling menu
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.'
        ]);
    }
}
