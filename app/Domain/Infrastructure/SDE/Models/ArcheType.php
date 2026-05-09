<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class ArcheType extends Model
{
    protected $table = 'sde.archetypes';
    protected $primaryKey = '_key';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        '_key',
        'hash',
        'description',
        'title',
    ];

    protected $casts = [
        'description' => 'array',
        'title' => 'array',
    ];
}
