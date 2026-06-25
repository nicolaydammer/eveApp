<?php

namespace App\Domain\IndustryCalculator\Actions;

use App\Domain\EVE\Models\SystemIndices;

class SystemCostIndices
{
    public function getIndices(int $systemId)
    {
        return SystemIndices::query()->find($systemId);
    }
}
