<?php

namespace App\Domain\EVE\Repositories;

use App\Domain\EVE\Models\Alliance;
use App\Domain\EVE\Repositories\CachedRepositoryInterface;
use App\Domain\Infrastructure\Esi\DTO\AllianceDTO;
use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;

class CachedAllianceRepository implements CachedRepositoryInterface
{
    public function find(int $id): ?EsiDtoInterface
    {
        $alliance = Alliance::query()->where('alliance_id', $id)->get();

        if ($alliance->isEmpty()) {
            return null;
        }

        $alliance = $alliance->first();

        return new AllianceDTO(
            $alliance->id,
            $alliance->alliance_id,
            $alliance->creator_corporation_id,
            $alliance->creator_id,
            $alliance->date_founded,
            $alliance->executor_corporation_id,
            $alliance->faction_id,
            $alliance->name,
            $alliance->ticker,
            $alliance->expires_at,
        );
    }

    public function save(EsiDtoInterface $esiDto): void
    {
        $data = $esiDto->toArray();

        Alliance::query()->updateOrCreate(['alliance_id' => $data['alliance_id']], $data);
    }
}
