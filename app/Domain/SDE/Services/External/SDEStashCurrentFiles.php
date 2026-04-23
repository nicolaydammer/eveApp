<?php

namespace App\Domain\SDE\Services\External;

use Illuminate\Support\Facades\Storage;

class SDEStashCurrentFiles
{
    private $eveDisk;

    public function __construct()
    {
        $this->eveDisk = Storage::disk('eveSDE');
    }

    public function stash(): void
    {
        $path = $this->eveDisk->path('_sde.jsonl');

        $SDEVersionFile = fopen($path, 'r');

        if (! $SDEVersionFile) {
            throw new \Exception('Could not open _sde.jsonl');
        }

        $files = $this->eveDisk->files();

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
}
