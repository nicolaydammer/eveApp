<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SDEVersion extends Model
{
    protected $table = 'sde.sde_version';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'version',
    ];
}
