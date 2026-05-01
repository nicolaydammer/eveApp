<?php

namespace App\Domain\Shared\User;

use App\Domain\Auth\Entities\User;

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
        $user->main_character_id = $mainCharacterId;
        $user->save();
    }
}
