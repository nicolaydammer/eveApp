<?php

namespace App\Domain\Infrastructure\Esi\Gateway;

use App\Domain\EVE\Repositories\CachedRepositoryInterface;
use App\Domain\Infrastructure\Concurrency\CacheSyncLock;
use App\Domain\Infrastructure\Esi\DataProviders\EsiDataProviderInterface;
use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;
use App\Domain\Infrastructure\Esi\jobs\AbstractEsiSyncJob;
use App\Domain\Infrastructure\Esi\Requests\EsiRequest;

class EsiGateway
{
    public function __construct(
        private CachedRepositoryInterface $cachedRepository,
        private EsiDataProviderInterface $esiDataProvider,
        private CacheSyncLock $cacheSyncLock,
        private string $resourceType,
        private string $syncJobClass
    ) {
        if (!is_subclass_of($this->syncJobClass, AbstractEsiSyncJob::class)) {
            throw new \InvalidArgumentException("{$this->syncJobClass} must extend AbstractEsiSyncJob");
        }
    }

    public function get(EsiRequest $esiRequest): EsiDtoInterface
    {
        $dto = $this->cachedRepository->find($esiRequest->id());

        if (is_null($dto)) {
            $dto = $this->esiDataProvider->provide($esiRequest);

            $this->cachedRepository->save($dto);

            return $dto;
        }

        if ($dto->isStale()) {
            $this->cacheSyncLock->acquire(
                "sync:{$this->resourceType}:{$esiRequest->id()}",
                60,
                fn() => dispatch(new $this->syncJobClass($esiRequest))
            );
        }

        return $dto;
    }
}
