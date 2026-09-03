<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class CorporationRoleGroup extends Model
{
    protected $table = 'sde.corporation_role_groups';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'appliesTo',
        'appliesToGrantable',
        'isDivisional',
        'isLocational',
        'name',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'isDivisional' => 'boolean',
            'isLocational' => 'boolean',
            'name' => 'array',
        ];
    }
}
