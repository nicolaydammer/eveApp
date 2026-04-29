<?php

namespace App\Domain\EVE\State;

use App\Domain\EVE\DTO\VerifyOauthData;
use App\Models\Character;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CharacterRepository
{
    public function find(int $characterId): ?Collection
    {
        return Character::query()->find($characterId)->get();
    }

    public function create(VerifyOauthData $verifyOauthData, User $user): void
    {
        $user->characters()->create([
            'character_id' => $verifyOauthData->CharacterId,
            'character_name' => $verifyOauthData->CharacterName,
            'access_token' => $verifyOauthData->accessToken,
            'refresh_token' => $verifyOauthData->refreshToken,
            'token_expires_at' => $verifyOauthData->expiresAt,
        ]);
    }

    public function update(VerifyOauthData $verifyOauthData): void
    {
        $character = Character::query()->findOrFail($verifyOauthData->CharacterId)->first();

        $character->update([
            'accessToken' => $verifyOauthData->accessToken,
            'refreshToken' => $verifyOauthData->refreshToken,
            'expires_at' => $verifyOauthData->expiresAt,
        ]);
    }
}
