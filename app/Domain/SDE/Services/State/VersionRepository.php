<?php

namespace App\Domain\SDE\Services\State;

use App\Models\SDE\SDEVersion;

// todo: get supported and current SDE version from DB and cache it for 2 hours.
// todo: be able to set current version of SDE.

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
