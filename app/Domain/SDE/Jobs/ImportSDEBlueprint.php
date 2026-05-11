<?php

namespace App\Domain\SDE\Jobs;

use App\Domain\SDE\Mapping\SDEModelResolver;

class ImportSDEBlueprint extends AbstractSDEJob implements SDEJobInterface
{
    public function __construct(string $modelName, array $data, bool $firstTime)
    {
        return parent::__construct($modelName, $data, $firstTime);
    }

    public function handle(SDEModelResolver $SDEModelResolver): void {}
}
