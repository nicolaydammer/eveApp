<?php

namespace App\Http\Controllers\Web\Admin;

use Inertia\Inertia;

class CharacterController
{
    public function index()
    {
        return Inertia::render('Admin/Characters');
    }
}
