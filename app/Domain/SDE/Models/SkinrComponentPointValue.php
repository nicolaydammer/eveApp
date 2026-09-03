<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SkinrComponentPointValue extends Model
{
    protected $table = 'sde.skinr_component_point_values';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'hash',
        '_value',
    ];

    protected $casts = [
        '_key' => 'integer',
        '_value' => 'array',
    ];
}
