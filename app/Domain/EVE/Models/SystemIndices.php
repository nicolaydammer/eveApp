<?php

namespace App\Domain\EVE\Models;

use Illuminate\Database\Eloquent\Model;

class SystemIndices extends Model
{
    protected $primaryKey = 'solar_system_id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $table = 'cache.industry_cost_indices';

    protected $fillable = [
        'solar_system_id',

        'manufacturing',
        'researching_material_efficiency',
        'researching_time_efficiency',
        'copying',
        'invention',
        'reaction',
        'reverse_engineering',
        'duplicating',

        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'manufacturing' => 'float',
            'researching_material_efficiency' => 'float',
            'researching_time_efficiency' => 'float',
            'copying' => 'float',
            'invention' => 'float',
            'reaction' => 'float',
            'reverse_engineering' => 'float',
            'duplicating' => 'float',

            'synced_at' => 'datetime',
        ];
    }
}
