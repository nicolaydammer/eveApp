<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolMap extends Model
{
    protected $table = 'sde.school_map';

    protected $primaryKey = '_key';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'schoolID',
        'solarSystemID',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'schoolID' => 'integer',
            'solarSystemID' => 'integer',
        ];
    }
}
