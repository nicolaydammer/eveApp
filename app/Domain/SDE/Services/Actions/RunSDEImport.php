<?php

namespace App\Domain\SDE\Services\Actions;

use App\Jobs\SDE\ImportSDEData;
use Illuminate\Support\Facades\Storage;

class RunSDEImport
{
    private $eveDisk;

    public function __construct()
    {
        $this->eveDisk = Storage::disk('eveSDE');
    }

    public function import(bool $firstTime, int $batchSize): int
    {
        $batch = [];
        $jobs = 0;

        $SDEFileNames = $this->eveDisk->files();

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
                        ImportSDEData::dispatch($SDEFileName, $batch, $firstTime);
                        $jobs++;
                        $batch = [];
                    }
                }

                fclose($SDEFile);

                if (! empty($batch)) {
                    ImportSDEData::dispatch($SDEFileName, $batch, $firstTime);
                    $jobs++;
                    $batch = [];
                }
            }
        } finally {
            if (is_resource($SDEFile)) {
                fclose($SDEFile);
            }
        }

        return $jobs;
    }
}
