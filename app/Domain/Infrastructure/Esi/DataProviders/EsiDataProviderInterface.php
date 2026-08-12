<?php

namespace App\Domain\Infrastructure\Esi\DataProviders;

use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;
use App\Domain\Infrastructure\Esi\Requests\EsiRequest;

interface EsiDataProviderInterface
{
    public function provide(EsiRequest $esiRequest): EsiDtoInterface;
}
