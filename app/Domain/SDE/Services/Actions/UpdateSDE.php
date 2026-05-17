<?php

namespace App\Domain\SDE\Services\Actions;

use App\Domain\SDE\DTO\SDEUpdatePlan;
use App\Domain\SDE\Services\External\Downloader;
use App\Domain\SDE\Services\External\Extractor;
use App\Domain\SDE\Services\External\FileStasher;
use App\Domain\SDE\Services\External\VersionFetcher;
use App\Domain\SDE\Services\State\StateRepository;
use App\Domain\SDE\Services\State\VersionRepository;

// todo: orchestrate the SDE sync process.
class UpdateSDE
{
    private StateRepository $stateRepository;

    private VersionFetcher $versionFetcher;

    private VersionRepository $versionRepository;

    private Downloader $downloader;

    private Extractor $extractor;

    private FileStasher $fileStasher;

    public function __construct(
        StateRepository $stateRepository,
        VersionFetcher $versionFetcher,
        VersionRepository $versionRepository,
        Downloader $downloader,
        Extractor $extractor,
        FileStasher $fileStasher,
    ) {
        $this->stateRepository = $stateRepository;
        $this->versionFetcher = $versionFetcher;
        $this->versionRepository = $versionRepository;
        $this->downloader = $downloader;
        $this->extractor = $extractor;
        $this->fileStasher = $fileStasher;
    }

    public function planSDEUpdate(): SDEUpdatePlan
    {
        $latestVersion = $this->versionFetcher->getVersion();
        $currentVersion = $this->versionRepository->getCurrentVersion();
        $supportedVersion = $this->versionRepository->getSupportedVersion();

        $needsImportRun = false;
        $firstTime = false;
        $isSupported = true;
        $stashCurrentSDEFiles = false;
        $needsDownload = false;
        $needsExtract = false;
        $canReinstall = false;

        // no files found to sync with, download and import needs to be true
        if (! $this->stateRepository->hasSDEFiles()) {
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
        if (($latestVersion <= $supportedVersion) && ($latestVersion !== $currentVersion) && ($currentVersion !== $supportedVersion) && $this->stateRepository->hasSDEFiles()) {

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
        $this->fileStasher->stash();
    }

    public function downloadNewSDEFiles(string $version): void
    {
        $this->downloader->download($version);
    }

    public function extractSDEFiles(string $version): void
    {
        $this->extractor->extract($version);
    }

    public function updateCurrentVersion(string $version): void
    {
        $this->versionRepository->setCurrentVersion($version);
    }
}
