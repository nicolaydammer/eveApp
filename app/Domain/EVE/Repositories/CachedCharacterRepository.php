<?php

namespace App\Domain\EVE\Repositories;

use App\Domain\EVE\Models\Character;
use App\Domain\EVE\Repositories\CachedRepositoryInterface;
use App\Domain\Infrastructure\Esi\DTO\CharacterDTO;
use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;

class CachedCharacterRepository implements CachedRepositoryInterface
{
    public function find(int $id): ?EsiDtoInterface
    {
        $character = Character::query()->where('CharacterID', $id)->get();

        if ($character->isEmpty()) {
            return null;
        }

        $character = $character->first();

        return new CharacterDTO(
            $character->id,
            $character->CharacterID,
            $character->alliance_id,
            $character->corporation_id,
            $character->faction_id,
            $character->bloodline_id,
            $character->race_id,
            $character->birthday,
            $character->description,
            $character->gender,
            $character->name,
            $character->security_status,
            $character->expires_at,
            $character->title
        );
    }

    public function save(EsiDtoInterface $esiDto): void
    {
        $data = $esiDto->toArray();

        Character::query()->updateOrCreate(['CharacterID' => $data['CharacterID']], $data);
    }
}
