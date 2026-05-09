<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class TypeList extends Model
{
    protected $table = 'sde.type_lists';
    protected $primaryKey = '_key';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        '_key',
        'hash',
        'name',
        'displayName',
        'displayDescription',
        'includedTypeIDs',
        'includedGroupIDs',
        'includedCategoryIDs',
        'excludedTypeIDs',
        'excludedGroupIDs',
        'excludedCategoryIDs',
    ];

    protected $casts = [
        'displayName' => 'array',
        'displayDescription' => 'array',
        'includedTypeIDs' => 'array',
        'includedGroupIDs' => 'array',
        'includedCategoryIDs' => 'array',
        'excludedTypeIDs' => 'array',
        'excludedGroupIDs' => 'array',
        'excludedCategoryIDs' => 'array',
    ];
}
