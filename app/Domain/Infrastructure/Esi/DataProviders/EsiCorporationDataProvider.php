<?php

namespace App\Domain\Infrastructure\Esi\DataProviders;

use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use App\Domain\Infrastructure\Esi\DTO\CorporationDTO;
use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;
use Illuminate\Support\Carbon;

class EsiCorporationDataProvider implements EsiDataProviderInterface
{
    public function __construct(private EsiClient $esi_client) {}

    public function provide(int $id): EsiDtoInterface
    {
        $esiData = $this->esi_client->get("/corporations/{$id}");

        dd($id, $esiData);

        return new CorporationDTO(
            null,
            $id,
            $esiData['alliance_id'] ?? null,
            $esiData['ceo_id'],
            $esiData['creator_id'],
            Carbon::parse($esiData['date_founded']),
            $esiData['description'] ?? null,
            $esiData['enlisted_faction_id'] ?? null,
            $esiData['friendly_fire'],
            $esiData['home_station_id'] ?? null,
            $esiData['member_count'],
            $esiData['name'],
            $esiData['palette'] ?? null,
            $esiData['shares'] ?? null,
            $esiData['state'],
            $esiData['tax_rates'],
            $esiData['ticker'],
            $esiData['type'],
            $esiData['url'] ?? null,
            $esiData['war_eligible'] ?? null,
            Carbon::now()->addMinutes(45)
        );
    }
}
