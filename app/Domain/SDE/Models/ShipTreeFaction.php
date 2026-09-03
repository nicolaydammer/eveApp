<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class ShipTreeFaction extends Model
{
    protected $table = 'sde.ship_tree_factions';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'hash',
        'description',
        'elements',
        'icon',
    ];

    protected $casts = [
        '_key' => 'integer',
        'description' => 'array',
        'elements' => 'array',
    ];
}
