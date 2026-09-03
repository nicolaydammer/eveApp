<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class EpicArc extends Model
{
    protected $table = 'sde.epic_arcs';

    protected $fillable = [
        '_key',
        'hash',
        'arcRestartInterval',
        'factionID',
        'iconID',
        'missions',
        'name',
    ];

    public $timestamps = false;
    public $incrementing = false;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'arcRestartInterval' => 'integer',
            'factionID' => 'integer',
            'iconID' => 'integer',
            'missions' => 'array',
            'name' => 'array',
        ];
    }
}
