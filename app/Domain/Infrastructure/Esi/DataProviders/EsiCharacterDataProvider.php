<?php

namespace App\Domain\Infrastructure\Esi\DataProviders;

use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use App\Domain\Infrastructure\Esi\DTO\CharacterDTO;
use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;
use Illuminate\Support\Carbon;

class EsiCharacterDataProvider implements EsiDataProviderInterface
{
    public function __construct(private EsiClient $esi_client) {}

    public function provide(int $id): EsiDtoInterface
    {
        $esiData = $this->esi_client->get("/characters/{$id}");

        return new CharacterDTO(
            null,
            $id,
            $esiData['alliance_id'] ?? null,
            $esiData['corporation_id'],
            $esiData['faction_id'] ?? null,
            $esiData['bloodline_id'],
            $esiData['race_id'],
            Carbon::parse($esiData['birthday']),
            $esiData['description'] ?? null,
            $esiData['gender'],
            $esiData['name'],
            $esiData['security_status'] ?? null,
            now()->addHours(12),
            $esiData['corporation_title'] ?? null,
            $esiData['character_title_id'] ?? null,
            $esiData['achievement_score'] ?? null
        );
    }
}
