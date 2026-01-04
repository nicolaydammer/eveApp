<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class SDEVersion extends Model
{
    protected $table = 'sde_version';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'version',
    ];
}
