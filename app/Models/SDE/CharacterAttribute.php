<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class CharacterAttribute extends Model
{
    protected $table = 'sde.character_attributes';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'description',
        'iconID',
        'name',
        'notes',
        'shortDescription',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'description' => 'array',
        'iconID' => 'integer',
        'name' => 'array',
        'notes' => 'string',
        'shortDescription' => 'string',
    ];
}
