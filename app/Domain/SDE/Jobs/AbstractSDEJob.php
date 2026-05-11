<?php

namespace App\Domain\SDE\Jobs;

use App\Domain\SDE\Mapping\SDEModelResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class AbstractSDEJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private string $modelName, private array $data, private bool $firstTime, private SDEModelResolver $modelResolver) {}
}
