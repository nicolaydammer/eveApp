<?php

namespace App\Domain\EVE\Repositories;

use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;

interface CachedRepositoryInterface
{
    public function find(int $id): ?EsiDtoInterface;
    public function save(EsiDtoInterface $data): void;
}
