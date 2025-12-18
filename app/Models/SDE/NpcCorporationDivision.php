<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NpcCorporationDivision extends Model
{
    use HasFactory;

    protected $table = 'npc_corporation_divisions';
    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'internalName',

        'displayName',
        'name',
        'leaderTypeName',
        'description',
    ];

    protected $casts = [
        '_key' => 'integer',

        'displayName' => 'array',
        'name' => 'array',
        'leaderTypeName' => 'array',
        'description' => 'array',
    ];
}
