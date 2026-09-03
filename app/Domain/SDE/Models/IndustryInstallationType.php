<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryInstallationType extends Model
{
    protected $table = 'sde.industry_installation_types';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'assemblyLines',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'assemblyLines' => 'array',
        ];
    }
}
