<?php

namespace App\Domain\Auth\State;

use App\Domain\Auth\DTO\VerifyOauthData;
use App\Domain\Auth\Entities\Character;
use App\Domain\Auth\Entities\User;

class CharacterRepository
{
    public function find(int $characterId): ?Character
    {
        return Character::query()->find($characterId, 'CharacterID');
    }

    public function create(VerifyOauthData $verifyOauthData, User $user): void
    {
        $user->characters()->create([
            'CharacterID' => $verifyOauthData->CharacterID,
            'CharacterName' => $verifyOauthData->CharacterName,
            'accessToken' => $verifyOauthData->accessToken,
            'refreshToken' => $verifyOauthData->refreshToken,
            'expires_at' => $verifyOauthData->expiresAt,
        ]);
    }

    public function update(VerifyOauthData $verifyOauthData, Character $character): void
    {
        $character->update([
            'accessToken' => $verifyOauthData->accessToken,
            'refreshToken' => $verifyOauthData->refreshToken,
            'expires_at' => $verifyOauthData->expiresAt,
        ]);
    }
}
