<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CharacterAttribute extends Model
{
    protected $table = 'character_attributes';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        '_key',
        'description',
        'iconID',
        'name',
        'notes',
        'shortDescription',
    ];

    protected $casts = [
        '_key'             => 'integer',
        'description'      => 'array',
        'iconID'           => 'integer',
        'name'             => 'array',
        'notes'            => 'string',
        'shortDescription' => 'string',
    ];
}
