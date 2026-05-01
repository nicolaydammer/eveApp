<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model representing a category from categories.jsonl (EVE Online SDE data).
 */
class Category extends Model
{
    protected $table = 'sde.categories';

    // The primary key is '_key'
    protected $primaryKey = '_key';

    // Disable incrementing since the key is provided in the data
    public $incrementing = false;

    // The data does include timestamps
    public $timestamps = false;

    protected $fillable = [
        '_key',
        'name',
        'iconID',
        'published',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'name' => 'array', // Casts the multilingual name to a PHP array/object
        'iconID' => 'integer',
        'published' => 'boolean',
    ];
}
