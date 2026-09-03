<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SkinrSlotCategory extends Model
{
    protected $table = 'sde.skinr_slot_categories';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'hash',
        'name',
    ];

    protected $casts = [
        '_key' => 'integer',
    ];
}
