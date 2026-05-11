<?php

namespace App\Domain\SDE\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class AbstractSDEJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private string $modelName, private array $data, private bool $firstTime) {}

    public function trimFileExtension(string $filename, string $extension): string
    {
        return substr($filename, 0, 0 - strlen($extension));
    }
}
