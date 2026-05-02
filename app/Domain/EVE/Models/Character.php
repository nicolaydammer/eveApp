<?php

namespace App\Domain\EVE\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $table = 'cache.characters';

    protected $fillable = [
        'CharacterID',
        'alliance_id',
        'corporation_id',
        'faction_id',
        'bloodline_id',
        'race_id',
        'birthday',
        'description',
        'gender',
        'name',
        'security_status',
        'title',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'birthday' => 'datetime'
    ];
}
