<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketGroup extends Model
{
    use HasFactory;

    protected $table = 'sde.market_groups';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'description',
        'hasTypes',
        'iconID',
        'name',
        'parentGroupID',
        'hash',
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
