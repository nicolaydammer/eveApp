<?php

namespace App\Domain\Synchronization\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegionMarketOrdersSynchronized
{
    use Dispatchable, SerializesModels;
}
