<?php

namespace App\Domain\Infrastructure\Esi\Gateway;

use App\Domain\EVE\Repositories\CachedRepositoryInterface;
use App\Domain\Infrastructure\Concurrency\CacheSyncLock;
use App\Domain\Infrastructure\Esi\DataProviders\EsiDataProviderInterface;
use App\Domain\Infrastructure\Esi\DTO\EsiDtoInterface;
use App\Domain\Infrastructure\Esi\jobs\AbstractEsiSyncJob;

/**
 * @template T
 */
class EsiGateway
{
    public function __construct(
        private CachedRepositoryInterface $cachedRepository,
        private EsiDataProviderInterface $esiDataProvider,
        private CacheSyncLock $cacheSyncLock,
        private string $resourceType, // e.g., 'character', 'corporation'
        private string $syncJobClass  // The class string for the Dispatcher)
    ) {
        if (!is_subclass_of($this->syncJobClass, AbstractEsiSyncJob::class)) {
            throw new \InvalidArgumentException("{$this->syncJobClass} must extend AbstractEsiSyncJob");
        }
    }

    public function get(int $id): EsiDtoInterface
    {
        $dto = $this->cachedRepository->find($id);

        if (is_null($dto)) {
            $dto = $this->esiDataProvider->provide($id);

            $this->cachedRepository->save($dto);

            return $dto;
        }

        if ($dto->isStale()) {
            $this->cacheSyncLock->acquire(
                "sync:{$this->resourceType}:{$id}",
                60,
                fn() => dispatch(new $this->syncJobClass($id))
            );
        }

        return $dto;
    }
}
