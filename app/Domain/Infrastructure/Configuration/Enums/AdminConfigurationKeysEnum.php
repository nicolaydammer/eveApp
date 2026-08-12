<?php

namespace App\Domain\Infrastructure\Configuration\Enums;

enum AdminConfigurationKeysEnum: string
{
    case MARKET_REGIONS = 'market_regions';
    case STRUCTURE_MARKETS = 'structure_markets';

    public function rules(): array
    {
        return match ($this) {
            self::MARKET_REGIONS => [
                'configuration' => ['required', 'array'],
                'configuration.*' => ['integer']
            ],
            self::STRUCTURE_MARKETS => [
                'configuration' => ['present', 'array'],

                'configuration.*' => ['required', 'array:structure,char'],
                'configuration.*.structure' => ['required', 'integer', 'min:1'],
                'configuration.*.char' => ['required', 'integer', 'min:1'],
            ],
        };
    }
}
