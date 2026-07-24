<?php

namespace App\Domains\Market\Enums;

enum MarketOrderRange: string
{
    case Station = 'station';
    case Region = 'region';
    case SolarSystem = 'solarsystem';

    case Jump1 = '1';
    case Jump2 = '2';
    case Jump3 = '3';
    case Jump4 = '4';
    case Jump5 = '5';

    case Jump10 = '10';
    case Jump20 = '20';
    case Jump30 = '30';
    case Jump40 = '40';

    public function isJumpRange(): bool
    {
        return is_numeric($this->value);
    }

    public function jumpCount(): ?int
    {
        return $this->isJumpRange()
            ? (int) $this->value
            : null;
    }

    public function isUnlimited(): bool
    {
        return $this === self::Region;
    }
}
