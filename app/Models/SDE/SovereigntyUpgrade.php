<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class SovereigntyUpgrade extends Model
{
    protected $table = 'sovereignty_upgrades';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'fuel' => 'array',
    ];
}
