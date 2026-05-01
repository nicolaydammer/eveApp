<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SovereigntyUpgrade extends Model
{
    protected $table = 'sde.sovereignty_upgrades';

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
