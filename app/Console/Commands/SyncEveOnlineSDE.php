<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SyncEveOnlineSDE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:syncSDE {--batch=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('starting sync of eve online SDE');

        $eveDisk = Storage::disk('eveSDE');

        $SDEFileNames = $eveDisk->files();

        $latestVersion = Http::get("https://developers.eveonline.com/static-data/tranquility/latest.jsonl");

        $this->confirm('Did you migrate to the latest database version?');

        // no files found to sync with
        if (count($SDEFileNames) < 2) {
            $this->info('No SDE files detected, trying to download the zipfile and extract them.');

            $bar = $this->output->createProgressBar(2);
            $bar->start();

            $SDEFileName = 'eve-online-static-data-' . $latestVersion['buildNumber'] . '-jsonl.zip';

            $eveDisk->makeDirectory('zipFiles');

            Http::timeout(600)
            ->withOptions([
                'sink' => $eveDisk->path('/zipFiles/' . $SDEFileName)
            ])
            ->get('https://developers.eveonline.com/static-data/tranquility/' . $SDEFileName);

            $bar->advance();

            $zipService = new ZipArchive();

            if ($zipService->open($eveDisk->path('zipFiles/' . $SDEFileName)) !== true) {
                $this->error('Could not extract files from zip.');
                return Command::FAILURE;
            }

            $zipService->extractTo($eveDisk->path(''));

            $bar->advance();

            $bar->finish();
            $this->newLine();
        }

        // todo: grab current installed version from database or from file?
        // new build and not supported?
        if ($latestVersion['buildNumber'] > 3110079) {
            $this->error('There is a new build! ('. $latestVersion['buildNumber'] . ') But it is not supported by the application, notifiy the developer!');
            return Command::FAILURE;
        }

        // new build and supported?
        // todo: remove or stash away current files and download new ones
        if ($latestVersion['buildNumber'] > 3110079) {

        }

        // count of files found
        $amountOfSDEFiles = count($SDEFileNames);
        $this->info('Amount of files found: ' . $amountOfSDEFiles);

        // show version of the sde and release date
        $sdeFile = Storage::disk('eveSDE')->get('_sde.jsonl');
        $sdeFileLines = explode('\n', trim($sdeFile));

        $sdeArray = [];
        foreach ($sdeFileLines as $line) {
            if (trim($line) !== '') {
                $sdeArray[] = json_decode($line, true);
            }
        }

        $this->info('SDE build number: ' . $sdeArray[0]['buildNumber'] . ', release date: ' . $sdeArray[0]['releaseDate']);

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
        $jobs = [];

        if ($this->getOutput()->isVerbose()) {
            $this->line('Batchsize is: ' . $batchSize);
        }

        // Create a progress bar for files, -1 for the _sde.jsonl file which doesn't contain data
        $bar = $this->output->createProgressBar($amountOfSDEFiles - 1);
        $bar->start();

        try {
            foreach ($SDEFileNames as $SDEFileName) {
                // skip this file, doesn't contain data
                if ($SDEFileName == '_sde.jsonl') {
                    continue;
                }

                $path = Storage::disk('eveSDE')->path($SDEFileName);

                // stream file
                $SDEFile = fopen($path, 'r');

                if (!$SDEFile) {
                    throw new \Exception('Could not open ' .  $SDEFileName);
                }

                while (!feof($SDEFile)) {
                    $line = trim(fgets($SDEFile));

                    if ($line === '') {
                        continue;
                    }

                    $batch[] = json_decode($line, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('JSON decode error: ' . json_last_error_msg());
                    }

                    // create a batch of json to batchSize (adjust if needed for performance reasons)
                    if (count($batch) >= $batchSize) {
                        // todo: create a job according to the file, assign the batch to it and put it in the jobs array

                        $batch = [];
                    }
                }

                fclose($SDEFile);

                if (!empty($batch)) {
                    // todo: create a job according to the file, assign the batch to it and put it in the jobs array

                    $batch = [];
                }

                $bar->advance();
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
                $this->line('started ' . count($jobs) . ' jobs');
            }
            $this->info('jobs started, check in a moment for updated data, check horizon for fails or other problems');
        }
    }
}
