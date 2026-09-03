<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class ShipTreeElement extends Model
{
    protected $table = 'sde.ship_tree_elements';

    protected $fillable = [
        '_key',
        'hash',
        'description',
        'icon',
        'name',
    ];

    public $timestamps = false;
    public $incrementing = false;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'description' => 'array',
            'name' => 'array',
        ];
    }
}
