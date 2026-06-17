<?php

namespace App\Domain\Dashboard\Factories;

use App\Domain\EVE\Repositories\CachedAllianceRepository;
use App\Domain\EVE\Repositories\CachedCharacterRepository;
use App\Domain\EVE\Repositories\CachedCorporationRepository;
use App\Domain\Infrastructure\Concurrency\CacheSyncLock;
use App\Domain\Infrastructure\Esi\DataProviders\EsiAllianceDataProvider;
use App\Domain\Infrastructure\Esi\DataProviders\EsiCharacterDataProvider;
use App\Domain\Infrastructure\Esi\DataProviders\EsiCorporationDataProvider;
use App\Domain\Infrastructure\Esi\Gateway\EsiGateway;
use App\Domain\Infrastructure\Esi\jobs\SyncEsiAlliance;
use App\Domain\Infrastructure\Esi\jobs\SyncEsiCharacter;
use App\Domain\Infrastructure\Esi\jobs\SyncEsiCorporation;
use Illuminate\Container\Container;

class EsiGatewayFactory
{
    private $gateways = [];

    public function __construct(private Container $container) {}

    public function character(): EsiGateway
    {
        return $this->gateways['character'] ??= new EsiGateway(
            $this->container->make(CachedCharacterRepository::class),
            $this->container->make(EsiCharacterDataProvider::class),
            $this->container->make(CacheSyncLock::class),
            'character',
            SyncEsiCharacter::class
        );
    }

    public function corporation(): EsiGateway
    {
        return $this->gateways['corporation'] ??= new EsiGateway(
            $this->container->make(CachedCorporationRepository::class),
            $this->container->make(EsiCorporationDataProvider::class),
            $this->container->make(CacheSyncLock::class),
            'corporation',
            SyncEsiCorporation::class
        );
    }

    public function alliance(): EsiGateway
    {
        return $this->gateways['alliance'] ??= new EsiGateway(
            $this->container->make(CachedAllianceRepository::class),
            $this->container->make(EsiAllianceDataProvider::class),
            $this->container->make(CacheSyncLock::class),
            'alliance',
            SyncEsiAlliance::class
        );
    }
}
