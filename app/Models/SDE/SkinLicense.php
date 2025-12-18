<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class SkinLicense extends Model
{
    protected $table = 'skin_licenses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'isSingleUse' => 'boolean',
    ];
}
