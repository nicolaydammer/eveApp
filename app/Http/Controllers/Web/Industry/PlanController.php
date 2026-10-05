<?php

namespace App\Http\Controllers\Web\Industry;

use App\Domain\IndustryCalculator\Actions\IndustryPlanAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class PlanController
{
    public function __construct(private IndustryPlanAction $industryPlanAction) {}

    public function index()
    {
        return Inertia::render('IndustryPlans');
    }

    public function listPlans(): JsonResponse
    {
        return response()->json($this->industryPlanAction->list());
    }

    public function getPlan(Request $request, int $id): JsonResponse
    {
        return response()->json($this->industryPlanAction->get($id));
    }

    public function savePlan(Request $request): JsonResponse
    {
        $data = $request->array('data');

        return response()->json($this->industryPlanAction->save($data));
    }

    public function updatePlan(Request $request, int $id): Response
    {
        $data = $request->array('data');

        $this->industryPlanAction->update($id, $data);

        return response()->noContent();
    }

    public function deletePlan(Request $request, int $id): Response
    {
        $this->industryPlanAction->delete($id);

        return response()->noContent();
    }
}
