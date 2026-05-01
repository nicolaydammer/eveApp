<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class TypeBonus extends Model
{
    protected $table = 'sde.type_bonuses';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'roleBonuses',
        'types',
        'iconID',
        'miscBonuses',
        'hash',
    ];

    // Cast array columns
    protected $casts = [
        'roleBonuses' => 'array',
        'types' => 'array',
        'miscBonuses' => 'array',
    ];
}
