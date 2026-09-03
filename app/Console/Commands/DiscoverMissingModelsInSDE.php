<?php

namespace App\Console\Commands;

use App\Domain\SDE\Services\Actions\DiscoverMissingSDEModels;
use App\Domain\SDE\Services\External\VersionFetcher;
use App\Domain\SDE\Services\State\VersionRepository;
use Illuminate\Console\Command;

class DiscoverMissingModelsInSDE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:sde';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discover missing models compared to newest SDE version for developer purposes';

    public function __construct(
        private DiscoverMissingSDEModels $discoverMissingSDEModels,
        private VersionRepository $versionRepository,
        private VersionFetcher $versionFetcher,
    ) {
        return parent::__construct();
    }

    public function handle()
    {
        $missing = $this->discoverMissingSDEModels->discover();

        $this->info("Current installed version: {$this->versionRepository->getCurrentVersion()}");
        $this->info("Current supported version: {$this->versionRepository->getSupportedVersion()}");
        $this->info("Current live version from CCP: {$this->versionFetcher->getVersion()}");

        $this->alert('Following files found that are not in the current version of the SDE:');
        foreach ($missing['filesWithoutModel'] as $file) {
            $this->line($file);
        }

        $this->alert('Following files found that are not implemented in the job:');
        foreach ($missing['modelsNotImplementedInMapping'] as $file) {
            $this->line($file);
        }
    }
}
