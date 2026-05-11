<?php

namespace App\Domain\SDE\Jobs;

use App\Domain\SDE\Mapping\SDEModelResolver;

interface SDEJobInterface
{
    public function handle(SDEModelResolver $SDEModelResolver): void;
}
