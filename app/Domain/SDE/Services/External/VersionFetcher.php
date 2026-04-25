<?php

namespace App\Domain\SDE\Services\External;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class VersionFetcher
{
    public function __construct() {}

    /** @return int */
    public function getVersion()
    {

        $value = Cache::get('sde.latest_version');

        if ($value !== null) {
            return $value;
        }

        return Cache::lock('sde.version.fetch', 10)->block(5, function () {
            $value = Cache::get('sde.latest_version');

            if ($value !== null) {
                return $value;
            }

            $value = $this->fetchFromEve();

            if ($value !== null) {
                Cache::put('sde.latest_version', $value, 7200);
            }

            return $value;
        });
    }

    private function fetchFromEve(): int
    {
        $eveRequest = Http::get('https://developers.eveonline.com/static-data/tranquility/latest.jsonl');

        return (int) $eveRequest['buildNumber'];
    }
}
