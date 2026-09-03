<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SystemWideEffect extends Model
{
    protected $table = 'sde.system_wide_effects';

    protected $primaryKey = '_key';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'dbuffs',
        'eligibleTypeListID',
        'environmentTypeID',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'dbuffs' => 'array',
            'eligibleTypeListID' => 'integer',
            'environmentTypeID' => 'integer',
        ];
    }
}
