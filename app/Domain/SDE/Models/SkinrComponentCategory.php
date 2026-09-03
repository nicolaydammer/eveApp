<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SkinrComponentCategory extends Model
{
    protected $table = 'sde.skinr_component_categories';

    protected $fillable = [
        '_key',
        'hash',
        'name',
    ];

    public $timestamps = false;
    public $incrementing = false;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
        ];
    }
}
