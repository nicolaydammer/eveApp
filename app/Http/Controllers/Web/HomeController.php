<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Home');
    }
}
