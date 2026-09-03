<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryAssemblyLine extends Model
{
    protected $table = 'sde.industry_assembly_lines';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'activityID',
        'baseCostMultiplier',
        'baseMaterialMultiplier',
        'baseTimeMultiplier',
        'description',
        'detailsPerCategory',
        'detailsPerGroup',
        'detailsPerTypeList',
        'name',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'activityID' => 'integer',
            'baseCostMultiplier' => 'float',
            'baseMaterialMultiplier' => 'float',
            'baseTimeMultiplier' => 'float',
            'detailsPerCategory' => 'array',
            'detailsPerGroup' => 'array',
            'detailsPerTypeList' => 'array',
        ];
    }
}
