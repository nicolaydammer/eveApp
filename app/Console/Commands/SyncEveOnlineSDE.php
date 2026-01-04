<?php

namespace App\Console\Commands;

use App\Jobs\ProcessSDEData;
use App\Models\SDE\SDEVersion;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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

    private $eveDisk;

    public function __construct()
    {
        $this->eveDisk = Storage::disk('eveSDE');

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

        $SDEFileNames = $this->eveDisk->files();

        $latestVersion = $this->getLatestSDEVersion();
        // 1 = current installed version
        // 2 = supported
        $currentVersion = SDEVersion::query()->find(1)?->version;
        $supportedVersion = SDEVersion::query()->find(2)->version;

        $needsImportRun = false;

        // no files found to sync with, download them
        if (count($SDEFileNames) == 0) {
            $this->info('No SDE files detected, trying to download the zipfile and extract them.');
            $this->downloadNewSDEFiles($latestVersion);
            $needsImportRun = true;
        }

        // new build and not supported?
        if (($latestVersion > $currentVersion) && ($latestVersion > $supportedVersion)) {
            $this->error('There is a new build! ('.$latestVersion.') But it is not supported by the application, notifiy the developer!');
        }

        // new build and supported?
        if (($latestVersion <= $supportedVersion) && ($latestVersion !== $currentVersion)) {
            $this->info('Upgrading SDE files to new version '.$latestVersion);
            $this->info('stashing the current SDEFiles in case for a rollback');

            $this->stashCurrentSDEFiles();

            $this->info('Downloading new SDE version');
            $this->downloadNewSDEFiles($latestVersion);
            $needsImportRun = true;
        }

        $firstTime = is_null($currentVersion) ? true : false;

        if (! $needsImportRun && ! $firstTime) {
            $needsImportRun = confirm('Do you want to run the SDE import again?');
        }

        if ($needsImportRun || $firstTime) {
            $this->importSDEdata($SDEFileNames, $firstTime);
        }
    }

    private function importSDEdata(array $SDEFileNames, bool $firstTime)
    {
        // count of files found
        $amountOfSDEFiles = count($SDEFileNames);
        $this->info('Amount of files found: '.$amountOfSDEFiles);

        // show version of the sde and release date
        $sdeFile = Storage::disk('eveSDE')->get('_sde.jsonl');
        $sdeFileLines = explode('\n', trim($sdeFile));

        $sdeArray = [];
        foreach ($sdeFileLines as $line) {
            if (trim($line) !== '') {
                $sdeArray[] = json_decode($line, true);
            }
        }

        $this->info('SDE build number: '.$sdeArray[0]['buildNumber'].', release date: '.$sdeArray[0]['releaseDate']);

        // verbose show list of all files found
        if ($this->getOutput()->isVerbose()) {
            $this->line('List of all files:');
            foreach ($SDEFileNames as $value) {
                $this->line($value);
            }
        }

        $this->info('start creating jobs for syncing eve SDE data...');

        // loop through the files, get the json and throw it into jobs, except for _sde.jsonl
        $batchSize = (int) $this->option('batch');
        $batch = [];
        $jobs = 0;

        if ($this->getOutput()->isVerbose()) {
            $this->line('Batchsize is: '.$batchSize);
        }

        // Create a progress bar for files, -1 for the _sde.jsonl file which doesn't contain data
        $bar = $this->output->createProgressBar($amountOfSDEFiles - 1);
        $bar->start();

        $sdeFile = null;
        $success = false;

        try {
            foreach ($SDEFileNames as $SDEFileName) {
                // skip this file, doesn't contain data
                if ($SDEFileName == '_sde.jsonl') {
                    continue;
                }

                $path = $this->eveDisk->path($SDEFileName);

                // stream file
                $SDEFile = fopen($path, 'r');

                if (! $SDEFile) {
                    throw new \Exception('Could not open '.$SDEFileName);
                }

                while (! feof($SDEFile)) {
                    $line = trim(fgets($SDEFile));

                    if ($line === '') {
                        continue;
                    }

                    $md5 = md5($line);
                    $jsonLine = json_decode($line, true);
                    $jsonLine['hash'] = $md5;
                    $batch[] = $jsonLine;

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('JSON decode error: '.json_last_error_msg());
                    }

                    // create a batch of json to batchSize (adjust if needed for performance reasons)
                    if (count($batch) >= $batchSize) {
                        ProcessSDEData::dispatch($SDEFileName, $batch, $firstTime);
                        $jobs++;
                        $batch = [];
                    }
                }

                fclose($SDEFile);

                if (! empty($batch)) {
                    ProcessSDEData::dispatch($SDEFileName, $batch, $firstTime);
                    $jobs++;
                    $batch = [];
                }

                $bar->advance();

                $success = true;
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        } finally {
            if (is_resource($SDEFile)) {
                fclose($SDEFile);
            }
            $bar->finish();
            $this->line('');
            if ($this->getOutput()->isVerbose()) {
                $this->line('started '.$jobs.' jobs');
            }

            if ($success) {
                $this->info('jobs started, check in a moment for updated data, check horizon for fails or other problems');
                SDEVersion::updateOrInsert(['id' => 1], ['id' => 1, 'version' => $sdeArray[0]['buildNumber']]);
            }
        }
    }

    private function stashCurrentSDEFiles(): void
    {
        $path = $this->eveDisk->path('_sde.jsonl');

        $SDEVersionFile = fopen($path, 'r');

        $files = $this->eveDisk->files();

        if (! $SDEVersionFile) {
            throw new \Exception('Could not open _sde.jsonl');
        }

        $currentVersion = '';
        while (! feof($SDEVersionFile)) {
            $line = trim(fgets($SDEVersionFile));

            if ($line == '') {
                continue;
            }

            $currentVersion = json_decode($line, true)['buildNumber'];
        }

        fclose($SDEVersionFile);

        $stashPath = 'oldSDE/'.$currentVersion;

        if (! $this->eveDisk->directoryExists($stashPath)) {
            $this->eveDisk->makeDirectory($stashPath);
        }

        foreach ($files as $file) {
            if (str_ends_with($file, '.jsonl')) {
                $this->eveDisk->move($file, $stashPath.'/'.$file);
            }
        }
    }

    private function downloadNewSDEFiles(int $latestVersion): void
    {
        $bar = $this->output->createProgressBar(2);
        $bar->start();

        $SDEFileName = 'eve-online-static-data-'.$latestVersion.'-jsonl.zip';

        if (! $this->eveDisk->directoryExists('zipFiles')) {
            $this->eveDisk->makeDirectory('zipFiles');
        }

        if (! $this->eveDisk->fileExists('zipFiles/'.$SDEFileName)) {
            Http::timeout(600)
                ->withOptions([
                    'sink' => $this->eveDisk->path('/zipFiles/'.$SDEFileName),
                ])
                ->get('https://developers.eveonline.com/static-data/tranquility/'.$SDEFileName);
        }

        $bar->advance();

        $zipService = new ZipArchive;

        if ($zipService->open($this->eveDisk->path('zipFiles/'.$SDEFileName)) !== true) {
            throw new Exception('Could not extract new SDE files from ZIP.');
        }

        $zipService->extractTo($this->eveDisk->path(''));

        $bar->advance();

        $bar->finish();
        $this->newLine();
    }

    private function getLatestSDEVersion(): int
    {
        $getVersion = Http::get('https://developers.eveonline.com/static-data/tranquility/latest.jsonl');

        return (int) $getVersion['buildNumber'];
    }
}
