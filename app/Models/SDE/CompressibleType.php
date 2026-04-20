<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class CompressibleType extends Model
{
    protected $table = 'compressible_types';

    protected $primaryKey = '_key';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'compressedTypeID',
        'hash',
    ];
}
