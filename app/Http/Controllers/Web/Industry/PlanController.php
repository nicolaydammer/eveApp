<?php

namespace App\Http\Controllers\Web\Industry;

use App\Domain\IndustryCalculator\Actions\IndustryPlanAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanController
{
    public function __construct(private IndustryPlanAction $industryPlanAction) {}

    public function listPlans(): JsonResponse
    {
        return $this->industryPlanAction->list();
    }

    public function getPlan(Request $request): JsonResponse
    {
        $name = $request->string('name');
        return $this->industryPlanAction->get($name);
    }

    public function savePlan(Request $request)
    {
        $data = $request->array('data');
        $name = $request->string('name');

        $this->industryPlanAction->save($name, $data);
    }
}
