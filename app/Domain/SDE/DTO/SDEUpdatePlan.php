<?php

namespace App\Domain\SDE\DTO;

final class SDEUpdatePlan
{
    public function __construct(
        public readonly int $latestVersion,
        public readonly ?int $currentVersion,
        public readonly bool $isSupported,
        public readonly bool $isFreshInstall,
        public readonly bool $stashCurrentSDEFiles,
        public readonly bool $needsDownload,
        public readonly bool $needsExtract,
        public readonly bool $needsImport,
        public readonly bool $canReinstall,
    ) {}
}
