<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DogmaUnit extends Model
{
    protected $table = 'dogma_units';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        '_key',
        'description',
        'displayName',
        'name',
    ];

    protected $casts = [
        '_key'        => 'integer',
        'description' => 'array',
        'displayName' => 'array',
        'name'        => 'string',
    ];
}
