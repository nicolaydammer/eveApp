<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SkinrSlotsToMaterials extends Model
{
    protected $table = 'sde.skinr_slots_to_materials';

    protected $primaryKey = '_key';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        '_value',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            '_value' => 'array',
        ];
    }
}
