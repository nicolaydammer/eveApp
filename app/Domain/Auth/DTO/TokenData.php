<?php

namespace App\Domain\Auth\DTO;

final class TokenData
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly int $expiresIn
    ) {}
}
