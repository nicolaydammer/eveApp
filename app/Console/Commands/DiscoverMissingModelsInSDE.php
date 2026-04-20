<?php

namespace App\Console\Commands;

use App\Jobs\ProcessSDEData;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DiscoverMissingModelsInSDE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:DiscoverMissingModelsInSDE';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discover missing models compared to newest SDE version for developer purposes';

    private $eveDisk;

    public function __construct()
    {
        $this->eveDisk = Storage::disk('eveSDE');

        return parent::__construct();
    }

    public function handle()
    {
        $SDEFileNames = $this->eveDisk->files();

        if (! in_array('_sde.jsonl', $SDEFileNames)) {
            $this->fail('No SDE files are present to compare with');
        }

        $latestVersion = SyncEveOnlineSDE::getLatestSDEVersion();

        try {
            $this->info('downloading latest SDE to look for differences...');

            $SDEFileName = 'eve-online-static-data-'.$latestVersion.'-jsonl.zip';

            $tmpDir = storage_path('app/tmp/'.uniqid());
            File::makeDirectory($tmpDir, 0755, true);

            $zipPath = $tmpDir.'/'.$latestVersion;

            Http::timeout(600)
                ->withOptions([
                    'sink' => $zipPath,
                ])
                ->get('https://developers.eveonline.com/static-data/tranquility/'.$SDEFileName);

            $zipService = new ZipArchive;

            if ($zipService->open($zipPath) !== true) {
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

            $this->alert('Following files found that are not in the current version of the SDE:');
            foreach ($filesWithoutModel as $filesWithoutModel) {
                $this->info($filesWithoutModel);
            }

            $modelsNotImplementedInJob = [];

            $keys = array_flip(ProcessSDEData::$models);

            foreach ($zipFileNames as $newVersionFile) {
                if ($newVersionFile == '_sde.jsonl') {
                    continue;
                }

                if (! in_array(substr($newVersionFile, 0, -6), $keys)) {
                    $modelsNotImplementedInJob[] = $newVersionFile;
                }
            }

            $this->alert('Following files found that are not implemented in the job:');
            foreach ($modelsNotImplementedInJob as $modelNotImplementedInJob) {
                $this->info($modelNotImplementedInJob);
            }

        } finally {
            File::deleteDirectory($tmpDir);
        }
    }
}
