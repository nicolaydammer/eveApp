<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table = 'certificates';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'description',
        'groupID',
        'name',
        'recommendedFor',
        'skillTypes',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'description' => 'array',
        'groupID' => 'integer',
        'name' => 'array',
        'recommendedFor' => 'array',
        'skillTypes' => 'array',
    ];
}
