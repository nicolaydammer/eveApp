<?php

namespace App\Domain\Infrastructure\Configuration\Enums;

use App\Domain\Infrastructure\Esi\Enums\Scope;
use Illuminate\Validation\Rule;

enum AdminConfigurationKeysEnum: string
{
    case MARKET_REGIONS = 'market_regions';
    case STRUCTURE_MARKETS = 'structure_markets';
    case ESI_SCOPES = 'esi_scopes';

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
            self::ESI_SCOPES => [
                'configuration' => ['present', 'array'],
                'configuration.*' => [
                    'string',
                    Rule::enum(Scope::class),
                ],
            ]
        };
    }
}
