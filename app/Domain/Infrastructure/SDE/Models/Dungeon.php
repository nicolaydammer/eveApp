<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class Dungeon extends Model
{
    protected $table = 'sde.dungeon';
    protected $primaryKey = '_key';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        '_key',
        'hash',
        'archetypeID',
        'factionID',
        'allowedShipsList',
        'description',
        'gameplayDescription',
        'name',
    ];

    protected $casts = [
        'allowedShipsList' => 'array',
        'description' => 'array',
        'gameplayDescription' => 'array',
        'name' => 'array',
    ];
}
