<?php

namespace App\Domain\IndustryCalculator\Actions;

use App\Domain\IndustryCalculator\Models\IndustryPlan;
use Auth;
use Illuminate\Http\JsonResponse;

class IndustryPlanAction
{
    public function save(string $name, array $values): void
    {
        $userId = Auth::user()->id;

        IndustryPlan::query()->updateOrCreate([
            'user_id' => $userId,
            'name' => $name
        ], $values);
    }

    public function get(string $name): JsonResponse
    {
        $userId = Auth::user()->id;
        $data = IndustryPlan::query()
            ->where('name', $name)
            ->where('user_id', $userId)
            ->first();

        if (empty($data)) {
            return response()->json(['error' => 'No plan with name: ' . $name . 'found.'], 404);
        }

        return response()->json($data);
    }

    public function list(): JsonResponse
    {
        $userId = Auth::user()->id;
        $data = IndustryPlan::query()->where('user_id', $userId)->get();
        return response()->json($data);
    }
}
