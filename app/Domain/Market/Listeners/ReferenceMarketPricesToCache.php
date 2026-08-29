<?php

namespace App\Domain\Market\Listeners;

use App\Domain\Synchronization\Events\ReferenceMarketPricesSynchronized;

class ReferenceMarketPricesToCache
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
    public function handle(ReferenceMarketPricesSynchronized $event): void {}
}
