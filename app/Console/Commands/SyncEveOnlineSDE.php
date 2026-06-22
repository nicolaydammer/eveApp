<?php

namespace App\Console\Commands;

use App\Domain\SDE\Services\Actions\RunSDEImport;
use App\Domain\SDE\Services\Actions\UpdateSDE;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;

class SyncEveOnlineSDE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:syncSDE {--batch=500}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private UpdateSDE $updateSDE;

    private RunSDEImport $runSDEImport;

    public function __construct(UpdateSDE $updateSDE, RunSDEImport $runSDEImport)
    {
        $this->updateSDE = $updateSDE;
        $this->runSDEImport = $runSDEImport;

        return parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('starting sync of eve online SDE');
        $this->warn('Do not interrupt this or you might leave the app in a broken state!');
        $ranMigrations = confirm('Did you migrate to the latest database version?');

        if (! $ranMigrations) {
            return Command::SUCCESS;
        }

        $plan = $this->updateSDE->planSDEUpdate();

        if ($plan->isFreshInstall) {
            $this->info('No SDE files detected');
        }

        if (! $plan->isSupported) {
            $this->error('There is a new build! (' . $plan->latestVersion . ') But it is not supported by the application, notifiy the developer!');
        }

        if ($plan->stashCurrentSDEFiles) {
            $this->info('stashing the current SDEFiles in case for a rollback');
            $bar = $this->output->createProgressBar(1);
            $bar->start();
            $this->updateSDE->stashCurrentSDEFiles();
            $bar->advance();
            $bar->finish();
            $this->newLine();
        }

        if ($plan->needsDownload) {
            $this->info('downloading new SDE files');
            $bar = $this->output->createProgressBar(1);
            $bar->start();
            $this->updateSDE->downloadNewSDEFiles($plan->latestVersion);
            $bar->advance();
            $bar->finish();
            $this->newLine();
        }

        if ($plan->needsExtract) {
            $this->info('extracting new SDE files');
            $bar = $this->output->createProgressBar(1);
            $bar->start();
            $this->updateSDE->extractSDEFiles($plan->latestVersion);
            $bar->advance();
            $bar->finish();
            $this->newLine();
        }

        if ($plan->needsImport && $plan->isSupported) {
            $this->info('importing new SDE data into the database');
            $this->info('This might take some time depending on other running jobs and hardware.');
            $countJobs = $this->runSDEImport->import($plan->isFreshInstall, (int) $this->option('batch'));
            $this->info('Started ' . $countJobs . ' jobs to import the SDE data');
            $this->updateSDE->updateCurrentVersion($plan->latestVersion);
        }

        $needsImportRun = false;

        if ($plan->canReinstall) {
            $needsImportRun = confirm('Do you want to run the SDE import again?');
        }

        if ($needsImportRun) {
            $this->info('importing new SDE data into the database');
            $this->info('This might take some time depending on other running jobs and hardware.');
            $countJobs = $this->runSDEImport->import($plan->isFreshInstall, (int) $this->option('batch'));
            $this->info('Started ' . $countJobs . ' jobs to import the SDE data');
        }
    }
}
