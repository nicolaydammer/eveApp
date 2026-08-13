<?php

namespace App\Domain\Auth\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'main_character_id',
        'remember_token',
    ];

    protected $hidden = [
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

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }
}
