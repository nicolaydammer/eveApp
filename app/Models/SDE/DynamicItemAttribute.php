<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class DynamicItemAttribute extends Model
{
    protected $table = 'dynamic_item_attributes';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'attributeIDs',
        'inputOutputMapping',
    ];

    protected $casts = [
        'attributeIDs' => 'array',
        'inputOutputMapping' => 'array',
    ];
}
