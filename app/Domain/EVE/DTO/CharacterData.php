<?php

namespace App\Domain\EVE\DTO;

final class CharacterData
{
    public function __construct(
        public readonly int $characterId,
        public readonly string $characterName
    ) {}
}
