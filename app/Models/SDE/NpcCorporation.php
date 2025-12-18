<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class NpcCorporation extends Model
{
    protected $table = 'npc_corporations';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'deleted' => 'boolean',
        'hasPlayerPersonnelManager' => 'boolean',
        'sendCharTerminationMessage' => 'boolean',
        'uniqueName' => 'boolean',
        'minSecurity' => 'float',
        'taxRate' => 'float',
        'description' => 'array',
        'name' => 'array',
        'allowedMemberRaces' => 'array',
        'corporationTrades' => 'array',
    ];
}
