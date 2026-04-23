<?php

namespace App\Domain\SDE\Services\Actions;

use App\Domain\SDE\Mapping\SDEModelResolver;
use App\Domain\SDE\Services\External\SDEDownloader;
use App\Domain\SDE\Services\External\SDEVersionService;
use Exception;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SDEDiscoverMissingModels
{
    private $eveDisk;

    private SDEModelResolver $modelResolver;

    private SDEVersionService $SDEVersionService;

    private SDEDownloader $SDEDownloader;

    public function __construct(SDEModelResolver $modelResolver, SDEVersionService $SDEVersionService, SDEDownloader $SDEDownloader)
    {
        $this->eveDisk = Storage::disk('eveSDE');
        $this->modelResolver = $modelResolver;
        $this->SDEVersionService = $SDEVersionService;
        $this->SDEDownloader = $SDEDownloader;
    }

    public function discover()
    {
        $SDEFileNames = $this->eveDisk->files();

        if (! in_array('_sde.jsonl', $SDEFileNames)) {
            throw new \LogicException('No SDE files are present to compare with');
        }

        $latestVersion = $this->SDEVersionService->getVersion();
        $SDEZipFileName = 'eve-online-static-data-'.$latestVersion.'-jsonl.zip';

        $this->SDEDownloader->download($latestVersion);

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
