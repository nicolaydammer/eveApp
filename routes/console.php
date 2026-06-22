<?php

use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    // call action of the synchronization domain -> they handle everything related to syncing.
})->everyMinute();
