<?php

namespace App\Domain\Shared\User;

use App\Models\User;

class UserRepository
{
    public function create(int $mainCharacterId): User
    {
        $user = User::create([
            'main_character_id' => $mainCharacterId,
        ]);

        return $user;
    }
}
