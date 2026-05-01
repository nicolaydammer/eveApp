<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    protected $table = 'sde.races';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'name',
        'description',
        'iconID',
        'shipTypeID',
        'skills',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'name' => 'array',
        'description' => 'array',
        'skills' => 'array',
        'iconID' => 'integer',
        'shipTypeID' => 'integer',
    ];
}
