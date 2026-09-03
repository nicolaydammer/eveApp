<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class CorporationRole extends Model
{
    protected $table = 'sde.corporation_roles';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'description',
        'name',
        'roleGroupIDs',
        'shortName',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'description' => 'array',
            'name' => 'array',
            'roleGroupIDs' => 'array',
        ];
    }
}
