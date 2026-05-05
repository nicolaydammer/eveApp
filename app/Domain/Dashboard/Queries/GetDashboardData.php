<?php

namespace App\Domain\Dashboard\Queries;

use App\Domain\Auth\Entities\Character;
use App\Domain\Dashboard\Queries\Factories\EsiGatewayFactory;
use Illuminate\Support\Facades\Auth;

class GetDashboardData
{
    // This class can be expanded in the future to include parameters for filtering or pagination
    public function __construct(private EsiGatewayFactory $esiGatewayFactory) {}

    public function query(): array
    {
        $esiCharacters = [];

        Auth::user()->characters->each(function (Character $character) use (&$esiCharacters) {
            $characterData = $this->esiGatewayFactory->character()->get($character->CharacterID)->toArray();
            $allianceData = $this->esiGatewayFactory->alliance()->get($characterData['alliance_id'])->toArray();
            $corpData = $this->esiGatewayFactory->corporation()->get($characterData['corporation_id'])->toArray();

            $esiCharacters[] = [
                'id' => $characterData['CharacterID'],
                'name' => $characterData['name'],
                'portrait' => 'https://images.evetech.net/characters/' . $characterData['CharacterID'] . '/portrait',
                'corpName' => $corpData['name'],
                'corpLogo' => 'https://images.evetech.net/corporations/' . $corpData['corporation_id'] . '/logo',
                'allianceName' => $allianceData['name'],
                'allianceLogo' => 'https://images.evetech.net/alliances/' . $allianceData['alliance_id'] . '/logo',
                'isMain' => $characterData['CharacterID'] === Auth::user()->main_character_id,
            ];
        });

        return [
            'characters' => $esiCharacters,
        ];
    }
}
