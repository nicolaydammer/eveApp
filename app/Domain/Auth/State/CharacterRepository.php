<?php

namespace App\Domain\Auth\State;

use App\Domain\Auth\DTO\VerifyOauthData;
use App\Domain\Auth\Entities\Character;
use App\Domain\Auth\Entities\User;

class CharacterRepository
{
    public function find(int $characterId): ?Character
    {
        return Character::query()->find($characterId);
    }

    public function create(VerifyOauthData $verifyOauthData, User $user): void
    {
        $user->characters()->create([
            'CharacterID' => $verifyOauthData->CharacterID,
            'CharacterName' => $verifyOauthData->CharacterName,
            'accessToken' => $verifyOauthData->accessToken,
            'refreshToken' => $verifyOauthData->refreshToken,
            'expires_at' => $verifyOauthData->expiresAt,
            'scopes' => $verifyOauthData->scopes,
        ]);
    }

    public function update(VerifyOauthData $verifyOauthData, Character $character, User $user): void
    {
        if ($character->user_id != $user->id) {
            $character->update([
                'accessToken' => $verifyOauthData->accessToken,
                'refreshToken' => $verifyOauthData->refreshToken,
                'expires_at' => $verifyOauthData->expiresAt,
                'scopes' => $verifyOauthData->scopes,
                'user_id' => $user->id,
            ]);
        } else {
            $character->update([
                'accessToken' => $verifyOauthData->accessToken,
                'refreshToken' => $verifyOauthData->refreshToken,
                'expires_at' => $verifyOauthData->expiresAt,
                'scopes' => $verifyOauthData->scopes,
            ]);
        }
    }
}
