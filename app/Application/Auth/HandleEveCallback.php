<?php

namespace App\Application\Auth;

use App\Domain\EVE\DTO\TokenData;
use App\Domain\EVE\External\SSOClient;

class HandleEveCallback
{
    private $SSOClient;

    public function __construct(SSOClient $SSOClient)
    {
        $this->SSOClient = $SSOClient;
    }

    public function handle(string $code)
    {
        $tokenData = $this->SSOClient->exchangeCode($code);

        $this->SSOClient->login(new TokenData(
            accessToken: $tokenData->accessToken,
            refreshToken: $tokenData->refreshToken,
            expiresIn: $tokenData->expiresIn
        ));

        return redirect()->route('home');
    }
}
