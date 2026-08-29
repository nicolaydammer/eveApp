<?php

namespace App\Domain\Market\Listeners;

use App\Domain\Synchronization\Events\StructureMarketOrdersSynchronized;

class StructureMarketDataToCache
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StructureMarketOrdersSynchronized $event): void {}
}
