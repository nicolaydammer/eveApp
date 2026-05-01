<?php

namespace App\Domain\Dashboard\Queries;

use Illuminate\Support\Facades\Auth;

class GetDashboardData
{
    // This class can be expanded in the future to include parameters for filtering or pagination

    public function query(): array
    {
        $characterData = Auth::user()->characters->map(fn ($c) => [
            'id' => $c->CharacterID,
            'name' => $c->CharacterName,
            'portrait' => 'https://images.evetech.net/characters/'.$c->CharacterID.'/portrait',
            'corpName' => '',
            'corpLogo' => '',
            'allianceName' => '',
            'allianceLogo' => '',
            'isMain' => $c->CharacterID === Auth::user()->main_character_id,
        ]);

        return [
            'characters' => $characterData,
        ];
    }
}
