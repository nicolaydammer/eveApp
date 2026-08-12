<?php

namespace App\Domain\Infrastructure\Esi\jobs;

use App\Domain\EVE\Repositories\CachedAllianceRepository;
use App\Domain\Infrastructure\Esi\DataProviders\EsiAllianceDataProvider;

class SyncEsiAlliance extends AbstractEsiSyncJob
{
    public function handle(
        CachedAllianceRepository $cachedAllianceRepository,
        EsiAllianceDataProvider $esiAllianceDataProvider
    ): void {
        $freshData = $esiAllianceDataProvider->provide($this->esiRequest);

        $cachedAllianceRepository->save($freshData);
    }
}
