<?php

namespace App\Domain\IndustryCalculator\Actions;

use App\Domain\IndustryCalculator\Models\IndustryPlan;
use Auth;
use Exception;

class IndustryPlanAction
{
    public function save(array $values): int
    {
        $userId = Auth::user()->id;
        $name = $values['name'];

        $plan = IndustryPlan::query()->updateOrCreate([
            'user_id' => $userId,
            'name' => $name
        ], $values);

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
        $data = IndustryPlan::query()->where('user_id', $userId)->get();
        return $data->toArray();
    }

    public function update(int $id, array $data)
    {
        $userId = Auth::user()->id;

        IndustryPlan::query()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'name' => $data['name'],
                'plan' => $data['plan']
            ]);
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
