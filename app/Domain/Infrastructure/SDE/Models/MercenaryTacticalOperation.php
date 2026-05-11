<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class MercenaryTacticalOperation extends Model
{
    protected $table = 'sde.mercenary_tactical_operations';

    protected $primaryKey = '_key';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'anarchyImpact',
        'developmentImpact',
        'infomorphBonus',
        'description',
        'dungeonID',
        'name',
        'hash',
    ];

    protected $casts = [
        'description' => 'array',
        'name' => 'array',
    ];
}
