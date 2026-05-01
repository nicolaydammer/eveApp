<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class DogmaAttributeCategory extends Model
{
    protected $table = 'sde.dogma_attribute_categories';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'name',
        'description',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'name' => 'string',
        'description' => 'string',
    ];
}
