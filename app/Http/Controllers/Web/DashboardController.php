<?php

namespace App\Http\Controllers\Web;

use App\Domain\Dashboard\Queries\GetDashboardData;
use App\Domain\Shared\User\UserRepository;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index(GetDashboardData $getDashboardData): \Inertia\Response
    {
        return inertia('Dashboard', $getDashboardData->query());
    }

    public function setMainCharacter(int $CharacterID, UserRepository $userRepository): void
    {
        $userRepository->setMainCharacter(Auth::user(), $CharacterID);
    }
}
