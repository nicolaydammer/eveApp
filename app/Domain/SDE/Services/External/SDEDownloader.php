<?php

namespace App\Domain\SDE\Services\External;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SDEDownloader
{
    private $eveDisk;

    public function __construct()
    {
        $this->eveDisk = Storage::disk('eveSDE');
    }

    public function download(string $version): void
    {
        $SDEFileName = 'eve-online-static-data-'.$version.'-jsonl.zip';

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
    }
}
