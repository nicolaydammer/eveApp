<?php

use App\Domain\Synchronization\Actions\RunSynchronizations;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    app(RunSynchronizations::class)->execute();
})->everyMinute();
