<?php

namespace App\Domain\EVE\DTO;

final class VerifyOauthData
{
    public function __construct(
        public readonly int $CharacterId,
        public readonly string $CharacterName,
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly \DateTime $expiresAt
    ) {}
}
