<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DbuffCollection extends Model
{
    use HasFactory;

    protected $table = 'dbuff_collections';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'aggregateMode',
        'developerDescription',
        'displayName',
        'operationName',
        'showOutputValueInUI',
        'itemModifiers',
        'locationGroupModifiers',
        'locationModifiers',
        'locationRequiredSkillModifiers',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'displayName' => 'array',
        'itemModifiers' => 'array',
        'locationGroupModifiers' => 'array',
        'locationModifiers' => 'array',
        'locationRequiredSkillModifiers' => 'array',
    ];
}
