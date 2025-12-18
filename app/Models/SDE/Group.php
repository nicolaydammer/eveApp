<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'categoryID',
        'name',
        'published',
        'useBasePrice',

        'anchorable',
        'anchored',
        'fittableNonSingleton',
    ];

    protected $casts = [
        '_key' => 'integer',
        'categoryID' => 'integer',
        'name' => 'array',
        'published' => 'boolean',
        'useBasePrice' => 'boolean',

        'anchorable' => 'boolean',
        'anchored' => 'boolean',
        'fittableNonSingleton' => 'boolean',
    ];
}
