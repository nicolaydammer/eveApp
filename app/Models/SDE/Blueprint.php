<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class Blueprint extends Model
{
    protected $table = 'blueprints';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'activities',
        'blueprintTypeID',
        'maxProductionLimit',
        'hash',
    ];

    protected $casts = [
        'activities' => 'array',
    ];
}
