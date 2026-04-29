<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'main_character_id',
        'remember_token',
    ];

    public function characters()
    {
        return $this->hasMany(Character::class);
    }

    public function mainCharacter()
    {
        return $this->hasOne(Character::class, 'CharacterID', 'main_character_id');
    }
}
