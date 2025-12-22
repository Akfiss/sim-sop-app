<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function index()
    {
        return redirect()->route('guide.show', 'welcome');
    }

    public function show($role)
    {
        $validRoles = ['welcome', 'login', 'publik', 'admin', 'pengusul', 'verifikator', 'direksi'];

        if (!in_array($role, $validRoles)) {
            abort(404);
        }

        return view('guide.index', compact('role'));
    }
}
