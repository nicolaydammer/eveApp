<?php

namespace App\Domain\IndustryCalculator\Actions;

use App\Domain\IndustryCalculator\Models\IndustryPlan;
use Auth;
use Exception;

class IndustryPlanAction
{
    public function create(string $name): int
    {
        $userId = Auth::user()->id;

        $plan = IndustryPlan::query()->create([
            'user_id' => $userId,
            'name' => $name,
            'plan' => []
        ]);

        return $plan->id;
    }

    public function get(int $id): array
    {
        $userId = Auth::user()->id;
        $data = IndustryPlan::query()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (empty($data)) {
            throw new Exception('No plan with id: ' . $id . ' found.', 404);
        }

        return $data->toArray();
    }

    public function list(): array
    {
        $userId = Auth::user()->id;
        $data = IndustryPlan::query()
            ->select(['id', 'name', 'created_at', 'updated_at'])
            ->where('user_id', $userId)
            ->get();
        return $data->toArray();
    }

    public function update(int $id, string $name, array $plan)
    {
        $userId = Auth::user()->id;

        // if we only send a name in the update, dont change the plan
        if (empty($plan)) {
            IndustryPlan::query()
                ->where('id', $id)
                ->where('user_id', $userId)
                ->update([
                    'name' => $name,
                ]);
        } else {
            IndustryPlan::query()
                ->where('id', $id)
                ->where('user_id', $userId)
                ->update([
                    'name' => $name,
                    'plan' => $plan
                ]);
        }
    }

    public function delete(int $id)
    {
        $userId = Auth::user()->id;

        IndustryPlan::query()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->delete();
    }
}
