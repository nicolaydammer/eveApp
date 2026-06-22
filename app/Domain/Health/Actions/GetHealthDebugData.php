<?php

namespace App\Domain\Health\Actions;

use App\Domain\Health\Models\HealthEvent;
use Illuminate\Database\Eloquent\Collection;

class GetHealthDebugData
{
    public function execute(?int $days = 7): Collection
    {
        $query = HealthEvent::query()
            ->orderBy('source')
            ->orderByDesc('last_seen_at');

        if ($days !== null) {
            $query->where(
                'last_seen_at',
                '>=',
                now()->subDays($days)
            );
        }

        return $query->get();
    }
}
