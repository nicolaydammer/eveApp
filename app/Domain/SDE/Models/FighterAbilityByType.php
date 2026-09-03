<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class FighterAbilityByType extends Model
{
    protected $table = 'sde.fighter_abilities_by_type';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'abilitySlot0',
        'abilitySlot1',
        'abilitySlot2',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'abilitySlot0' => 'array',
            'abilitySlot1' => 'array',
            'abilitySlot2' => 'array',
        ];
    }
}
