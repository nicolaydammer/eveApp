<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DogmaAttributeCategory extends Model
{
    protected $table = 'dogma_attribute_categories';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        '_key',
        'name',
        'description',
    ];

    protected $casts = [
        '_key'        => 'integer',
        'name'        => 'string',
        'description' => 'string',
    ];
}
