<?php

namespace App\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MarketController
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Market');
    }
}
