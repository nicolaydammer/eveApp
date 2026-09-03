<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryTargetFilter extends Model
{
    protected $table = 'sde.industry_target_filters';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'categoryIDs',
        'groupIDs',
        'name',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'categoryIDs' => 'array',
            'groupIDs' => 'array',
        ];
    }
}
