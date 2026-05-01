<?php

namespace App\Domain\Auth\DTO;

final class VerifyOauthData
{
    public function __construct(
        public readonly int $CharacterID,
        public readonly string $CharacterName,
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly \DateTime $expiresAt
    ) {}
}
