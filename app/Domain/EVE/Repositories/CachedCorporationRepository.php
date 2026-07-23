<?php

namespace App\Domain\EVE\Repositories;


use App\Domain\EVE\Models\Corporation;
use App\Domain\EVE\Repositories\CachedRepositoryInterface;
use App\Domain\Infrastructure\Esi\DTO\CorporationDTO;
use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;

class CachedCorporationRepository implements CachedRepositoryInterface
{
    public function find(int $id): ?EsiDtoInterface
    {
        $corporation = Corporation::query()->where('corporation_id', $id)->get();

        if ($corporation->isEmpty()) {
            return null;
        }

        $corporation = $corporation->first();

        return new CorporationDTO(
            $corporation->id,
            $corporation->corporation_id,
            $corporation->alliance_id,
            $corporation->ceo_id,
            $corporation->creator_id,
            $corporation->date_founded,
            $corporation->description,
            $corporation->enlisted_faction_id,
            $corporation->friendly_fire,
            $corporation->home_station_id,
            $corporation->member_count,
            $corporation->name,
            $corporation->palette,
            $corporation->shares,
            $corporation->state,
            $corporation->tax_rates,
            $corporation->ticker,
            $corporation->type,
            $corporation->url,
            $corporation->war_eligible,
            $corporation->expires_at
        );
    }

    public function save(EsiDtoInterface $esiDto): void
    {
        $data = $esiDto->toArray();

        Corporation::query()->updateOrCreate(['corporation_id' => $data['corporation_id']], $data);
    }
}
