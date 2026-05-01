<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class DogmaUnit extends Model
{
    protected $table = 'sde.dogma_units';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'description',
        'displayName',
        'name',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'description' => 'array',
        'displayName' => 'array',
        'name' => 'string',
    ];
}
