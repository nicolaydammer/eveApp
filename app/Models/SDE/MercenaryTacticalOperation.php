<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class MercenaryTacticalOperation extends Model
{
    protected $table = 'mercenary_tactical_operations';

    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'anarchy_impact',
        'development_impact',
        'infomorph_bonus',
        'description',
        'name',
        'hash',
    ];

    protected $casts = [
        'description' => 'array',
        'name' => 'array',
    ];
}
