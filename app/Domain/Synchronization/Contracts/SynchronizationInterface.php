<?php

namespace App\Domain\Synchronization\Contracts;

use App\Domain\Synchronization\Models\Synchronization;

interface SynchronizationInterface
{
    public function run(Synchronization $synchronization): void;
}
