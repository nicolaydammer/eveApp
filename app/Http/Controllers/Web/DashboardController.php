<?php

namespace App\Http\Controllers\Web;

use App\Domain\Dashboard\Queries\GetDashboardData;

class DashboardController
{
    public function index(GetDashboardData $getDashboardData): \Inertia\Response
    {
        return inertia('Dashboard', $getDashboardData->query());
    }
}
