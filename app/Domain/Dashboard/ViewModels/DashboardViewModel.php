<?php

namespace App\Domain\Dashboard\ViewModels;

use App\Domain\Infrastructure\Esi\DTO\AllianceDTO;
use App\Domain\Infrastructure\Esi\DTO\CharacterDTO;
use App\Domain\Infrastructure\Esi\DTO\CorporationDTO;
use Illuminate\Contracts\Support\Arrayable;

class DashboardViewModel implements Arrayable
{
    public function __construct(
        private int $mainCharacterId,
        private CharacterDTO $characterDto,
        private CorporationDTO $corporationDto,
        private ?AllianceDTO $allianceDto = null,

    ) {}

    public function toArray(): array
    {
        return [
            'id'           => $this->characterDto->CharacterID,
            'name'         => $this->characterDto->name,
            'isMain'       => $this->characterDto->CharacterID === $this->mainCharacterId,
            'portrait'     => "https://images.evetech.net/characters/{$this->characterDto->CharacterID}/portrait?size=128",
            'corporation'  => [
                'name' => $this->corporationDto->name,
                'logo' => "https://images.evetech.net/corporations/{$this->corporationDto->corporation_id}/logo?size=64",
            ],
            'alliance'     => $this->allianceDto ? [
                'name' => $this->allianceDto->name,
                'logo' => "https://images.evetech.net/alliances/{$this->allianceDto->alliance_id}/logo?size=64",
            ] : null,
        ];
    }
}
