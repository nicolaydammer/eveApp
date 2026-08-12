<?php

namespace Database\Seeders;

use App\Domain\Infrastructure\Configuration\Repositories\ConfigurationRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function __construct(private ConfigurationRepository $configurationRepository) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! $this->configurationRepository->has('esi_scopes')) {
            $this->configurationRepository->set('esi_scopes', []);
        }

        if (! $this->configurationRepository->has('market_regions')) {
            $this->configurationRepository->set('market_regions', []);
        }

        if (! $this->configurationRepository->has('structure_markets')) {
            $this->configurationRepository->set('structure_markets', []);
        }
    }
}
