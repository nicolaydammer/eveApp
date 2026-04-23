<?php

namespace App\Domain\SDE\Services\State;

use Illuminate\Support\Facades\Storage;

class SDEState
{
    protected $eveDisk;

    public function __construct()
    {
        $this->eveDisk = Storage::disk('eveSDE');
    }

    public function hasSDEFiles(): bool
    {
        return $this->eveDisk->exists('_sde.jsonl');
    }
}
