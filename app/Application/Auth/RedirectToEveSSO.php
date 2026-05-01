<?php

namespace App\Application\Auth;

use App\Domain\Auth\Service\AuthService;

class RedirectToEveSSO
{
    private authService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function redirect(): string
    {
        return $this->authService->getAuthorizationUrl();
    }
}
