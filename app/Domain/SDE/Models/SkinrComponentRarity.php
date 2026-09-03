<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SkinrComponentRarity extends Model
{
    protected $table = 'sde.skinr_component_rarities';

    protected $fillable = [
        '_key',
        'hash',
        'name',
        'rank',
    ];

    public $timestamps = false;
    public $incrementing = false;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'name' => 'array',
            'rank' => 'integer',
        ];
    }
}
