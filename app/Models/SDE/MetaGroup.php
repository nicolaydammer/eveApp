<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetaGroup extends Model
{
    use HasFactory;

    protected $table = 'meta_groups';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'name',
        'description',
        'color',
        'iconID',
        'iconSuffix',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'name' => 'array',
        'description' => 'array',
        'color' => 'array',
        'iconID' => 'integer',
    ];
}
