<?php

namespace App\Domain\Dashboard\Queries;

use App\Domain\Auth\Entities\Character;
use App\Domain\Infrastructure\Esi\Gateway\EsiGateway;
use Illuminate\Support\Facades\Auth;

class GetDashboardData
{
    // This class can be expanded in the future to include parameters for filtering or pagination

    public function query(): array
    {
        /** @var EsiGateway $gateway */
        $gateway = app('esi.character');

        $esiCharacters = [];

        Auth::user()->characters->each(function (Character $character) use ($gateway, &$esiCharacters) {
            $esiCharacters[] = $gateway->get($character->CharacterID);
        });

        return [
            'characters' => $esiCharacters,
        ];
    }
}
