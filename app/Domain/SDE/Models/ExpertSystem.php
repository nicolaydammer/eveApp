<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertSystem extends Model
{
    protected $table = 'sde.expert_systems';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'associatedShipTypes',
        'durationDays',
        'hidden',
        'internalName',
        'retired',
        'skillsGranted',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'associatedShipTypes' => 'array',
            'durationDays' => 'integer',
            'hidden' => 'boolean',
            'retired' => 'boolean',
            'skillsGranted' => 'array',
        ];
    }
}
