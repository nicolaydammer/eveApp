<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ancestry extends Model
{
    use HasFactory;

    protected $table = 'ancestries';
    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'bloodlineID',
        'charisma',
        'intelligence',
        'memory',
        'perception',
        'willpower',
        'description',
        'iconID',
        'name',
        'shortDescription',
    ];

    protected $casts = [
        '_key' => 'integer',
        'bloodlineID' => 'integer',
        'charisma' => 'integer',
        'intelligence' => 'integer',
        'memory' => 'integer',
        'perception' => 'integer',
        'willpower' => 'integer',
        'description' => 'array',
        'iconID' => 'integer',
        'name' => 'array',
    ];
}
