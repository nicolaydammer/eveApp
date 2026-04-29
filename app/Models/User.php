<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'main_character_id',
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
