<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SystemDbuffEmitter extends Model
{
    protected $table = 'sde.system_dbuff_emitters';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'dbuffs',
        'duration',
        'excludeProtected',
        'interval',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'dbuffs' => 'array',
            'delaySeconds' => 'integer',
            'radius' => 'integer',
        ];
    }
}
