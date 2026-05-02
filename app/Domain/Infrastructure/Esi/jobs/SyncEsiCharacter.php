<?php

namespace App\Domain\Infrastructure\Esi\jobs;

use App\Domain\EVE\Repositories\CachedCharacterRepository;
use App\Domain\Infrastructure\Esi\DataProviders\EsiCharacterDataProvider;

class SyncEsiCharacter extends AbstractEsiSyncJob
{
    public function handle(
        CachedCharacterRepository $cachedCharacterRepository,
        EsiCharacterDataProvider $esiCharacterDataProvider
    ): void {
        $freshData = $esiCharacterDataProvider->provide($this->entityId);

        $cachedCharacterRepository->save($freshData);
    }
}
