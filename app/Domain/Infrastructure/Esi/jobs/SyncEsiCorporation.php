<?php

namespace App\Domain\Infrastructure\Esi\jobs;

use App\Domain\EVE\Repositories\CachedCorporationRepository;
use App\Domain\Infrastructure\Esi\DataProviders\EsiCorporationDataProvider;

class SyncEsiCorporation extends AbstractEsiSyncJob
{
    public function handle(
        CachedCorporationRepository $cachedCorporationRepository,
        EsiCorporationDataProvider $esiCorporationDataProvider
    ): void {
        $freshData = $esiCorporationDataProvider->provide($this->entityId);

        $cachedCorporationRepository->save($freshData);
    }
}
