<?php

namespace App\Http\Controllers\Web\Market;

use App\Domain\Auth\State\CharacterRepository;
use App\Domain\Infrastructure\Configuration\Repositories\ConfigurationRepository;
use App\Domain\Infrastructure\Esi\Clients\EsiClient;
use App\Domain\Infrastructure\Esi\Requests\Universe\GetStructureInformation;
use App\Domain\SDE\Services\Actions\ResolveSystemName;
use Cache;
use Illuminate\Http\JsonResponse;

class MarketController
{
    public function __construct(
        private ConfigurationRepository $configurationRepository,
        private ResolveSystemName $resolveSystemName,
        private CharacterRepository $characterRepository,
        private EsiClient $esiClient,
    ) {}

    public function index(): JsonResponse
    {
        $structureConfig = $this->getStructureConfig();
        $systemIds = $this->getSystemIdsFromConfig();

        $structureData = $this->getStructureDataFromEsi($structureConfig);
        $systemNames = $this->resolveSystemName->resolveFromSystemIds($systemIds);

        return response()->json($this->fetchMarketCache($systemNames, $structureData));
    }

    public function referencePrices(): JsonResponse
    {
        return response()->json(Cache::tags(config('cacheTags.market'))->get('referencePrices') ?? []);
    }

    private function getStructureConfig(): array
    {
        if (! $this->configurationRepository->has('structure_markets')) {
            return [];
        }

        return $this->configurationRepository->get('structure_markets')['configuration'];
    }

    private function getSystemIdsFromConfig(): array
    {
        if (! $this->configurationRepository->has('market_regions')) {
            return [];
        }

        return $this->configurationRepository->get('market_regions')['configuration'];
    }

    private function getStructureDataFromEsi(array $structureConfig): array
    {
        $structureData = [];

        foreach ($structureConfig as $config) {
            $key = 'esi.universe.structures.' . $config['structure'];
            $structure = Cache::get($key);

            if (empty($structure)) {
                $character = $this->characterRepository->find($config['char']);
                $esiRequest = new GetStructureInformation($character, $config['structure']);
                $structure = $this->esiClient->get($esiRequest);

                Cache::put($key, $structure, now()->addDay());
            }

            $structureData[$config['structure']] = $structure;
        }


        return $structureData;
    }

    private function fetchMarketCache(array $systemNames, array $structureData): array
    {
        $marketData = [];

        foreach ($systemNames as $systemName) {
            $marketData[$systemName['system']] = cache::tags(config('cacheTags.market'))->get('system.' . $systemName['_key']);
        }

        foreach ($structureData as $key => $structure) {
            $marketData[$structure['name']] = cache::tags(config('cacheTags.market'))->get('structure.' . $key);
        }

        return $marketData;
    }
}
