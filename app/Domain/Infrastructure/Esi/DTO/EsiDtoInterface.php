<?php

namespace App\Domain\Infrastructure\Esi\DTO;

interface EsiDtoInterface
{
    public function isStale(): bool;
    public function toArray(): array;
}
