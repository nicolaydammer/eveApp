<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketGroup extends Model
{
    use HasFactory;

    protected $table = 'market_groups';
    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'description',
        'hasTypes',
        'iconID',
        'name',
        'parentGroupID',
    ];

    protected $casts = [
        '_key' => 'integer',
        'description' => 'array',
        'hasTypes' => 'boolean',
        'iconID' => 'integer',
        'name' => 'array',
        'parentGroupID' => 'integer',
    ];
}
