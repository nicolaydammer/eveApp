<?php

namespace App\Domain\SDE\Services\External;

use Exception;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SDEExtractor
{
    private $eveDisk;

    public function __construct()
    {
        $this->eveDisk = Storage::disk('eveSDE');
    }

    // todo: extract SDE zip in right location.
    public function extract(string $version): void
    {
        $zipService = new ZipArchive;

        $SDEFileName = 'eve-online-static-data-'.$version.'-jsonl.zip';

        if ($zipService->open($this->eveDisk->path('zipFiles/'.$SDEFileName)) !== true) {
            throw new Exception('Could not extract new SDE files from ZIP.');
        }

        $zipService->extractTo($this->eveDisk->path(''));
    }
}
