<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class NpcCharacter extends Model
{
    protected $table = 'sde.npc_characters';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'bloodlineID',
        'ceo',
        'corporationID',
        'gender',
        'locationID',
        'name',
        'raceID',
        'startDate',
        'description',
        'agent',
        'uniqueName',
        'skills',
        'ancestryID',
        'careerID',
        'schoolID',
        'specialityID',
        'hash',
    ];

    protected $casts = [
        'bloodlineID' => 'integer',
        'ceo' => 'boolean',
        'corporationID' => 'integer',
        'gender' => 'boolean',
        'locationID' => 'integer',
        'name' => 'array',
        'raceID' => 'integer',
        'startDate' => 'datetime',
        'uniqueName' => 'boolean',
        'skills' => 'array',
        'ancestryID' => 'integer',
        'careerID' => 'integer',
        'schoolID' => 'integer',
        'specialityID' => 'integer',
    ];
}
