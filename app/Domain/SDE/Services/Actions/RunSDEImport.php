<?php

namespace App\Domain\SDE\Services\Actions;

use App\Domain\SDE\Mapping\SDEJobResolver;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class RunSDEImport
{
    private Filesystem $eveDisk;
    private SDEJobResolver $SDEJobResolver;

    public function __construct(SDEJobResolver $SDEJobResolver)
    {
        $this->eveDisk = Storage::disk('eveSDE');
        $this->SDEJobResolver = $SDEJobResolver;
    }

    public function import(bool $firstTime, int $batchSize): int
    {
        $batch = [];
        $jobs = 0;
        $job = '';

        $SDEFiles = $this->eveDisk->files();

        try {
            foreach ($SDEFiles as $SDEFile) {
                // skip this file, doesn't contain data
                if ($SDEFile == '_sde.jsonl') {
                    continue;
                }

                $job = $this->SDEJobResolver->resolveJob($SDEFile);

                $path = $this->eveDisk->path($SDEFile);

                // stream file
                $file = fopen($path, 'r');

                if (! $file) {
                    throw new \Exception('Could not open ' . $SDEFile);
                }

                while (! feof($file)) {
                    $line = trim(fgets($file));

                    if ($line === '') {
                        continue;
                    }

                    $md5 = md5($line);
                    $jsonLine = json_decode($line, true);
                    $jsonLine['hash'] = $md5;
                    $batch[] = $jsonLine;

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('JSON decode error: ' . json_last_error_msg());
                    }

                    // create a batch of json to batchSize (adjust if needed for performance reasons)
                    if (count($batch) >= $batchSize) {

                        $job::dispatch($SDEFile, $batch, $firstTime);
                        $jobs++;
                        $batch = [];
                    }
                }

                fclose($file);

                if (! empty($batch)) {
                    $job::dispatch($SDEFile, $batch, $firstTime);
                    $jobs++;
                    $batch = [];
                }
            }
        } finally {
            if (is_resource($file)) {
                fclose($file);
            }
        }

        return $jobs;
    }
}
