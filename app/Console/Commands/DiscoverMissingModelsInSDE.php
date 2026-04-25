<?php

namespace App\Console\Commands;

use App\Domain\SDE\Services\Actions\DiscoverMissingSDEModels;
use Illuminate\Console\Command;

class DiscoverMissingModelsInSDE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:DiscoverMissingModelsInSDE';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discover missing models compared to newest SDE version for developer purposes';

    private DiscoverMissingSDEModels $discoverMissingSDEModels;

    public function __construct(DiscoverMissingSDEModels $discoverMissingSDEModels)
    {
        $this->discoverMissingSDEModels = $discoverMissingSDEModels;

        return parent::__construct();
    }

    public function handle()
    {
        $missing = $this->discoverMissingSDEModels->discover();

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
