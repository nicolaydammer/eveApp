<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class SovereigntyUpgrade extends Model
{
    protected $table = 'sovereignty_upgrades';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'fuel',
        'mutually_exclusive_group',
        'power_allocation',
        'workforce_allocation',
        'workforce_production',
        'power_production',
        'hash',
    ];

    protected $casts = [
        'fuel' => 'array',
    ];
}
