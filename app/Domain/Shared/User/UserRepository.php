<?php

namespace App\Domain\Shared\User;

use App\Domain\Auth\Entities\User;
use Exception;

class UserRepository
{
    public function create(int $mainCharacterId): User
    {
        $user = User::create([
            'main_character_id' => $mainCharacterId,
        ]);

        return $user;
    }

    public function setMainCharacter(User $user, int $mainCharacterId): void
    {
        $characters = $user->characters()->get();

        if ($characters->where('CharacterID', $mainCharacterId)->count() === 0) {
            throw new Exception('Character not found');
        }

        $user->main_character_id = $mainCharacterId;
        $user->save();
    }

    public function hasUsers(): bool
    {
        return User::query()->exists();
    }

    public function setAdmin(User $user, bool $isAdmin): void
    {
        $user->is_admin = $isAdmin;
        $user->save();
    }
}
