<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class TypeDogma extends Model
{
    protected $table = 'type_dogmas';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'dogmaAttributes',
        'dogmaEffects',
        'hash',
    ];

    // Cast array columns
    protected $casts = [
        'dogmaAttributes' => 'array',
        'dogmaEffects' => 'array',
    ];
}
