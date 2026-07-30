<?php

namespace App\Domain\Infrastructure\Configuration\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $table = 'configuration';

    public $fillable = [
        'name',
        'configuration'
    ];

    protected $casts = [
        'configuration' => 'array'
    ];
}
