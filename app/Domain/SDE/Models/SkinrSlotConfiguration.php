<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SkinrSlotConfiguration extends Model
{
    protected $table = 'sde.skinr_slot_configurations';

    protected $fillable = [
        '_key',
        'hash',
        'allowAllShips',
        'config',
        'name',
        'priority',
        'ships',
    ];

    public $timestamps = false;
    public $incrementing = false;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'allowAllShips' => 'boolean',
            'config' => 'array',
            'priority' => 'integer',
            'ships' => 'array',
        ];
    }
}
