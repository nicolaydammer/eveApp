<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryModifierSource extends Model
{
    protected $table = 'sde.industry_modifier_sources';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'copying',
        'invention',
        'manufacturing',
        'reaction',
        'researchMaterial',
        'researchTime',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'copying' => 'array',
            'invention' => 'array',
            'manufacturing' => 'array',
            'reaction' => 'array',
            'researchMaterial' => 'array',
            'researchTime' => 'array',
        ];
    }
}
