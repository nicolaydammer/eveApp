<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterTitle extends Model
{
    protected $table = 'sde.character_titles';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'name',
        'hash'
    ];

    protected $casts = [
        'name' => 'array',
    ];
}
