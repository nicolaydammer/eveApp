<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class CompressibleType extends Model
{
    protected $table = 'sde.compressible_types';

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
