<?php

namespace App\Domain\SDE\Jobs;


use App\Domain\SDE\Jobs\AbstractSDEJob;
use App\Domain\SDE\Jobs\SDEJobInterface;
use App\Domain\SDE\Mapping\SDEModelResolver;
use Illuminate\Support\Facades\DB;

class ImportSDEData extends AbstractSDEJob implements SDEJobInterface
{

    /**
     * Create a new job instance.
     */
    public function __construct(private string $modelName, private array $data, private bool $firstTime) {}

    /**
     * Execute the job.
     */
    public function handle(SDEModelResolver $SDEModelResolver): void
    {
        // get model name
        $modelName = substr($this->modelName, 0, -6);
        $modelName = $SDEModelResolver->resolveModelName($modelName);

        $modelClass = "App\\Domain\\Infrastructure\\SDE\\Models\\{$modelName}";
        $modelClassInstance = new $modelClass;
        $table = $modelClassInstance->getTable();
        $fillables = $modelClassInstance->getFillable();
        $unsetKeys = [];

        // make all keys populated
        $allKeys = [];
        foreach ($this->data as $row) {
            $allKeys = array_unique(array_merge($allKeys, array_keys($row)));
        }

        foreach ($this->data as &$row) {
            foreach ($allKeys as $key) {
                if (! array_key_exists($key, $row)) {
                    $row[$key] = null;
                }
            }

            if (! $this->firstTime) {
                // throw out stuff if its not needed for an update
                $dbData = DB::table($table)->select('hash')->where('_key', '=', $row['_key'])->get();
                if (count($dbData) == 1 && $dbData->first()->hash == $row['hash']) {
                    $unsetKeys[] = $row['_key'];
                }
            }
        }

        if (! $this->firstTime) {
            $unsetKeys = array_flip($unsetKeys);

            $this->data = array_filter(
                $this->data,
                fn($row) => ! isset($unsetKeys[$row['_key']])
            );
        }

        foreach ($this->data as &$row) { // Use reference so changes apply directly
            array_walk($row, function (&$value, $key) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
            });
        }

        unset($row);


        if (! empty($this->data)) {
            $modelClass::query()->upsert($this->data, ['_key'], $fillables);
        }
    }
}
