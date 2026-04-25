<?php

namespace App\Domain\SDE\Services\Actions;

use App\Domain\SDE\Mapping\SDEModelResolver;
use App\Domain\SDE\Services\External\Downloader;
use App\Domain\SDE\Services\External\VersionFetcher;
use Exception;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DiscoverMissingSDEModels
{
    private $eveDisk;

    private SDEModelResolver $modelResolver;

    private VersionFetcher $versionFetcher;

    private Downloader $downloader;

    public function __construct(SDEModelResolver $modelResolver, VersionFetcher $versionFetcher, Downloader $downloader)
    {
        $this->eveDisk = Storage::disk('eveSDE');
        $this->modelResolver = $modelResolver;
        $this->versionFetcher = $versionFetcher;
        $this->downloader = $downloader;
    }

    public function discover()
    {
        $SDEFileNames = $this->eveDisk->files();

        if (! in_array('_sde.jsonl', $SDEFileNames)) {
            throw new \LogicException('No SDE files are present to compare with');
        }

        $latestVersion = $this->versionFetcher->getVersion();
        $SDEZipFileName = 'eve-online-static-data-'.$latestVersion.'-jsonl.zip';

        $this->downloader->download($latestVersion);

        $zipService = new ZipArchive;

        if ($zipService->open($this->eveDisk->path('/zipFiles/'.$SDEZipFileName)) !== true) {
            throw new Exception('Could not extract new SDE files from ZIP.');
        }

        $zipFileNames = [];

        for ($i = 0; $i < $zipService->numFiles; $i++) {
            $name = $zipService->getNameIndex($i);

            if (! str_ends_with($name, '/')) {
                $zipFileNames[] = basename($name);
            }
        }

        $filesWithoutModel = [];

        foreach ($zipFileNames as $newVersionFile) {
            if (! in_array($newVersionFile, $SDEFileNames)) {
                $filesWithoutModel[] = $newVersionFile;
            }
        }

        $modelsNotImplementedInMapping = [];

        $keys = array_flip($this->modelResolver->getAll());

        foreach ($zipFileNames as $newVersionFile) {
            if ($newVersionFile == '_sde.jsonl') {
                continue;
            }

            if (! in_array(substr($newVersionFile, 0, -6), $keys)) {
                $modelsNotImplementedInMapping[] = $newVersionFile;
            }
        }

        return [
            'filesWithoutModel' => $filesWithoutModel,
            'modelsNotImplementedInMapping' => $modelsNotImplementedInMapping,
        ];
    }
}
