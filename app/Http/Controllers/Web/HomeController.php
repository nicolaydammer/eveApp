<?php

namespace App\Http\Controllers\Web;

use Inertia\Inertia;

class HomeController
{
    public function index()
    {
        return Inertia::render('Home');
    }
}
