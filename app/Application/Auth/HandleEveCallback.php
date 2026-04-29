<?php

namespace App\Application\Auth;

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

        $characterData = $this->SSOClient->getCharacter($tokenData->accessToken);

        return $characterData;
    }
}
