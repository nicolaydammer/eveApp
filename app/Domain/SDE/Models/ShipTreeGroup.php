<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class ShipTreeGroup extends Model
{
    protected $table = 'sde.ship_tree_groups';

    protected $fillable = [
        '_key',
        'hash',
        'description',
        'elements',
        'icon',
        'iconLarge',
        'iconSmall',
        'iconSmallNPC',
        'name',
        'preReqSkills',
    ];

    public $timestamps = false;
    public $incrementing = false;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'description' => 'array',
            'elements' => 'array',
            'name' => 'array',
            'preReqSkills' => 'array',
        ];
    }
}
