<?php

namespace App\Domain\SDE\Mapping;

use App\Domain\SDE\Jobs\ImportSDEData;
use App\Domain\SDE\Jobs\SDEJobInterface;

class SDEJobResolver
{
    protected $jobMapping = [];

    public function resolveJob(string $sdeFileName): string
    {
        return !empty($this->jobMapping[$sdeFileName]) ? $this->jobMapping[$sdeFileName] : ImportSDEData::class;
    }

    public function getAll(): array
    {
        return $this->jobMapping;
    }
}
