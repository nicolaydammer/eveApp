<?php

namespace App\Domain\Synchronization\Helpers;

use Carbon\Carbon;

class SynchronizationScheduler
{
    public static function avoidDowntime(Carbon $nextRun): Carbon
    {
        $downtimeStart = $nextRun->copy()->setTime(11, 0);
        $downtimeEnd = $nextRun->copy()->setTime(11, 30);

        if ($nextRun->between($downtimeStart, $downtimeEnd)) {
            return $downtimeEnd;
        }

        return $nextRun;
    }
}
