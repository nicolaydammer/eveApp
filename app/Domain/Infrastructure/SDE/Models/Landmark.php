<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landmark extends Model
{
    use HasFactory;

    protected $table = 'sde.landmarks';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'description',
        'locationID',
        'iconID',
        'name',
        'position',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'description' => 'array',
        'locationID' => 'integer',
        'name' => 'array',
    ];
}
