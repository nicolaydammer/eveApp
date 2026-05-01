<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NpcCorporationDivision extends Model
{
    use HasFactory;

    protected $table = 'sde.npc_corporation_divisions';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'internalName',
        'displayName',
        'name',
        'leaderTypeName',
        'description',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'displayName' => 'array',
        'name' => 'array',
        'leaderTypeName' => 'array',
        'description' => 'array',
    ];
}
