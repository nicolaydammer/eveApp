<?php

namespace App\Domain\Health\Enums;

enum HealthSource: string
{
    case Authentication = 'authentication';
    case Esi = 'esi';
    case Industry = 'industry';
    case Market = 'market';
    case Synchronization = 'synchronization';
}
