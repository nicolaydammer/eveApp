<?php

namespace App\Http\Controllers\Web\Admin;

use App\Domain\Infrastructure\Esi\Enums\Scope;
use Inertia\Inertia;

class ScopeController
{
    public function index()
    {
        return Inertia::render('Admin/Scopes');
    }

    public function listScopes()
    {
        return Scope::toArray();
    }
}
