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
        };
    }
}
