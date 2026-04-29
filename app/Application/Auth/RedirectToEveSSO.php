<?php

namespace App\Application\Auth;

use App\Domain\EVE\External\SSOClient;

class RedirectToEveSSO
{
    private $ssoClient;

    public function __construct(SSOClient $ssoClient)
    {
        $this->ssoClient = $ssoClient;
    }

    public function redirect(): string
    {
        return $this->ssoClient->getAuthorizationUrl();
    }
}
