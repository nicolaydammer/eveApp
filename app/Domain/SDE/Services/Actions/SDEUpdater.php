<?php

namespace App\Domain\SDE\Services\Actions;

use App\Domain\SDE\DTO\SDEUpdatePlan;
use App\Domain\SDE\Services\External\SDEDownloader;
use App\Domain\SDE\Services\External\SDEExtractor;
use App\Domain\SDE\Services\External\SDEStashCurrentFiles;
use App\Domain\SDE\Services\External\SDEVersionService;
use App\Domain\SDE\Services\State\SDEState;
use App\Domain\SDE\Services\State\SDEVersionManager;

// todo: orchestrate the SDE sync process.
class SDEUpdater
{
    private SDEState $SDEState;

    private SDEVersionManager $SDEVersionManager;

    private SDEVersionService $SDEVersionService;

    private SDEDownloader $SDEDownloader;

    private SDEExtractor $SDEExtractor;

    private SDEStashCurrentFiles $SDEStashCurrentFiles;

    public function __construct(
        SDEState $SDEState,
        SDEVersionManager $SDEVersionManager,
        SDEVersionService $SDEVersionService,
        SDEDownloader $SDEDownloader,
        SDEExtractor $SDEExtractor,
        SDEStashCurrentFiles $SDEStashCurrentFiles,
    ) {
        $this->SDEState = $SDEState;
        $this->SDEVersionManager = $SDEVersionManager;
        $this->SDEVersionService = $SDEVersionService;
        $this->SDEDownloader = $SDEDownloader;
        $this->SDEExtractor = $SDEExtractor;
        $this->SDEStashCurrentFiles = $SDEStashCurrentFiles;
    }

    public function planSDEUpdate(): SDEUpdatePlan
    {
        $latestVersion = $this->SDEVersionService->getVersion();
        $currentVersion = $this->SDEVersionManager->getCurrentVersion();
        $supportedVersion = $this->SDEVersionManager->getSupportedVersion();

        $needsImportRun = false;
        $firstTime = false;
        $isSupported = true;
        $stashCurrentSDEFiles = false;
        $needsDownload = false;
        $needsExtract = false;
        $canReinstall = false;

        // no files found to sync with, download and import needs to be true
        if (! $this->SDEState->hasSDEFiles()) {
            $firstTime = true;
            $needsDownload = true;
            $needsExtract = true;
            $needsImportRun = true;
        }

        // new build and not supported
        if (($latestVersion > $currentVersion) && ($latestVersion > $supportedVersion)) {
            $isSupported = false;
        }

        // new build and supported
        if (($latestVersion <= $supportedVersion) && ($latestVersion !== $currentVersion) && ($currentVersion !== $supportedVersion)) {

            $stashCurrentSDEFiles = true;
            $needsDownload = true;
            $needsExtract = true;
            $needsImportRun = true;
        }

        if ($currentVersion == $supportedVersion) {
            $canReinstall = true;
        }

        return new SDEUpdatePlan(
            latestVersion: $latestVersion,
            currentVersion: $currentVersion,
            stashCurrentSDEFiles: $stashCurrentSDEFiles,
            needsImport: $needsImportRun,
            isFreshInstall: $firstTime,
            isSupported: $isSupported,
            needsDownload: $needsDownload,
            needsExtract: $needsExtract,
            canReinstall: $canReinstall,
        );
    }

    public function stashCurrentSDEFiles(): void
    {
        $this->SDEStashCurrentFiles->stash();
    }

    public function downloadNewSDEFiles(string $version): void
    {
        $this->SDEDownloader->download($version);
    }

    public function extractSDEFiles(string $version): void
    {
        $this->SDEExtractor->extract($version);
    }

    public function updateCurrentVersion(string $version): void
    {
        $this->SDEVersionManager->setCurrentVersion($version);
    }
}
