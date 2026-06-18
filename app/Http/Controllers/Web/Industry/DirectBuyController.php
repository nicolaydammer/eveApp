<?php

namespace App\Http\Controllers\Web\Industry;

class DirectBuyController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $_key)
    {
        $action = new \App\Domain\IndustryCalculator\Actions\BlueprintDirectBuy();
        return $action->directBuy($_key);
    }
}
