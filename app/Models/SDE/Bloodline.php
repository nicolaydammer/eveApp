<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class Bloodline extends Model
{
    protected $table = 'sde.bloodlines';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'name',
        'description',
        'charisma',
        'intelligence',
        'memory',
        'perception',
        'willpower',
        'iconID',
        'corporationID',
        'raceID',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'name' => 'array',
        'description' => 'array',
        'charisma' => 'integer',
        'intelligence' => 'integer',
        'memory' => 'integer',
        'perception' => 'integer',
        'willpower' => 'integer',
        'iconID' => 'integer',
        'corporationID' => 'integer',
        'raceID' => 'integer',
    ];
}
