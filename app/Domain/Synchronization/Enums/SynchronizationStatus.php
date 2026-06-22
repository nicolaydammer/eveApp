<?php

namespace App\Domain\Synchronization\Enums;

enum SynchronizationStatus: string
{
    case Pending = 'pending';
    case Running = 'running';
    case Success = 'success';
    case Failed = 'failed';
    case Skipped = 'skipped';

    public function isFinished(): bool
    {
        return match ($this) {
            self::Success,
            self::Failed,
            self::Skipped => true,

            self::Pending,
            self::Running => false,
        };
    }

    public function isRunning(): bool
    {
        return $this === self::Running;
    }

    public function isSuccessful(): bool
    {
        return $this === self::Success;
    }

    public function hasFailed(): bool
    {
        return $this === self::Failed;
    }
}
