<?php

namespace App\Domain\Infrastructure\Esi\DataProviders;

use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;

interface EsiDataProviderInterface
{
    public function provide(int $id): EsiDtoInterface;
}
