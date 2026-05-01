<?php

namespace App\Application\Auth;

use App\Domain\Auth\Service\AuthService;

class HandleEveCallback
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(string $code)
    {
        $tokenData = $this->authService->exchangeCode($code);

        $characterData = $this->authService->verifyLogin($tokenData);

        $this->authService->authenticateCharacter($characterData);

        return redirect()->route('dashboard');
    }
}
