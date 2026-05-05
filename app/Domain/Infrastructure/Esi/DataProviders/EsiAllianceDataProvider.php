<?php

namespace App\Domain\Infrastructure\Esi\DataProviders;

use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use App\Domain\Infrastructure\Esi\DTO\AllianceDTO;
use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;
use Illuminate\Support\Carbon;

class EsiAllianceDataProvider implements EsiDataProviderInterface
{
    public function __construct(private EsiClient $esi_client) {}

    public function provide(int $id): EsiDtoInterface
    {
        $esiData = $this->esi_client->get("/alliances/{$id}");

        return new AllianceDTO(
            null,
            $id,
            $esiData['creator_corporation_id'],
            $esiData['creator_id'],
            Carbon::parse($esiData['date_founded']),
            $esiData['executor_corporation_id'] ?? null,
            $esiData['faction_id'] ?? null,
            $esiData['name'],
            $esiData['ticker'],
            Carbon::now()->addMinutes(45)
        );
    }
}
