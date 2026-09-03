<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SkinrSlot extends Model
{
    protected $table = 'sde.skinr_slots';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'hash',
        'allowedDesignComponentCategories',
        'category',
        'name',
    ];

    protected $casts = [
        '_key' => 'integer',
        'allowedDesignComponentCategories' => 'array',
        'category' => 'integer',
        'name' => 'array',
    ];
}
