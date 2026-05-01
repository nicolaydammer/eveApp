<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicItemAttribute extends Model
{
    protected $table = 'sde.dynamic_item_attributes';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'attributeIDs',
        'inputOutputMapping',
        'hash',
    ];

    protected $casts = [
        'attributeIDs' => 'array',
        'inputOutputMapping' => 'array',
    ];
}
