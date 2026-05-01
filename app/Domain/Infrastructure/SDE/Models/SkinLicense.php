<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SkinLicense extends Model
{
    protected $table = 'sde.skin_licenses';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'duration',
        'licenseTypeID',
        'skinID',
        'isSingleUse',
        'hash',
    ];

    protected $casts = [
        'isSingleUse' => 'boolean',
    ];
}
