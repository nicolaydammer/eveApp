<?php

namespace App\Domain\SDE\Mapping;

use App\Domain\SDE\Jobs\ImportSDEBlueprint;
use App\Domain\SDE\Jobs\ImportSDEData;

class SDEJobResolver
{
    protected $jobMapping = [
        'blueprints.jsonl' => ImportSDEBlueprint::class,
    ];

    public function resolveJob(string $sdeFileName): string
    {
        return !empty($this->jobMapping[$sdeFileName]) ? $this->jobMapping[$sdeFileName] : ImportSDEData::class;
    }

    public function getAll(): array
    {
        return $this->jobMapping;
    }
}
