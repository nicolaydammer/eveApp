<?php

namespace App\Domain\Infrastructure\Esi\Requests\Market;

use App\Domain\Infrastructure\Esi\Requests\EsiRequest;
use Override;

class ListMarketPricesRequest extends EsiRequest
{
    #[Override]
    public function endpoint(): string
    {
        return '/markets/prices';
    }

    #[Override]
    public function id(): int|string|null
    {
        return null;
    }
}
