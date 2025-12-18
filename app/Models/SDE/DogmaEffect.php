<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DogmaEffect extends Model
{
    use HasFactory;

    protected $table = 'dogma_effects';
    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'description',
        'dischargeAttributeID',
        'disallowAutoRepeat',
        'distribution',
        'durationAttributeID',
        'effectCategoryID',
        'electronicChance',
        'falloffAttributeID',
        'guid',
        'isAssistance',
        'isOffensive',
        'isWarpSafe',
        'modifierInfo',
        'name',
        'propulsionChance',
        'published',
        'rangeAttributeID',
        'rangeChance',
        'trackingSpeedAttributeID',
    ];

    protected $casts = [
        '_key' => 'integer',
        'description' => 'array',
        'dischargeAttributeID' => 'integer',
        'disallowAutoRepeat' => 'boolean',
        'distribution' => 'integer',
        'durationAttributeID' => 'integer',
        'effectCategoryID' => 'integer',
        'electronicChance' => 'boolean',
        'falloffAttributeID' => 'integer',
        'isAssistance' => 'boolean',
        'isOffensive' => 'boolean',
        'isWarpSafe' => 'boolean',
        'modifierInfo' => 'array',
        'propulsionChance' => 'boolean',
        'published' => 'boolean',
        'rangeAttributeID' => 'integer',
        'rangeChance' => 'boolean',
        'trackingSpeedAttributeID' => 'integer',
    ];
}
