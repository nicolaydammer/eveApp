<?php

namespace App\Http\Controllers\Web\Industry;

class FullTreeController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $_key)
    {
        $action = new \App\Domain\IndustryCalculator\Actions\BlueprintTree();
        return $action->getTree($_key);
    }
}
