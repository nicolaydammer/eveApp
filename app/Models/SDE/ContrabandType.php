<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContrabandType extends Model
{
    use HasFactory;

    protected $table = 'sde.contraband_types';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'factions',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'factions' => 'array',
    ];
}
