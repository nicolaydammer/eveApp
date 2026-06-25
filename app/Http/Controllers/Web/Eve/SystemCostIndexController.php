<?php

namespace App\Http\Controllers\Web\Eve;

use App\Domain\IndustryCalculator\Actions\SystemCostIndices;
use Illuminate\Http\Request;

class SystemCostIndexController
{
    public function __construct(private SystemCostIndices $system_cost_indices) {}

    public function __invoke(Request $request, int $system)
    {
        return $this->system_cost_indices->getIndices($system);
    }
}
