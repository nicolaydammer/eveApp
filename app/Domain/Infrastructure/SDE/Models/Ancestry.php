<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ancestry extends Model
{
    use HasFactory;

    protected $table = 'sde.ancestries';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

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
        'hash',
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
