<?php

namespace App\Domain\SDE\Services\State;

use App\Domain\Infrastructure\SDE\Models\SDEVersion;

class VersionRepository
{
    public function __construct() {}

    public function getCurrentVersion(): ?int
    {
        return SDEVersion::query()->find('current_version')?->version ?? null;
    }

    public function setCurrentVersion(int $version): void
    {
        SDEVersion::query()->updateOrCreate(
            ['id' => 'current_version'],
            ['version' => $version]
        );
    }

    public function getSupportedVersion(): int
    {
        return SDEVersion::query()->find('supported_version')->version;
    }

    public function setSupportedVersion(int $version): void
    {
        SDEVersion::query()->updateOrCreate(
            ['id' => 'supported_version'],
            ['version' => $version]
        );
    }
}
