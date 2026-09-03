<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class MilitaryCampaign extends Model
{
    protected $table = 'sde.military_campaigns';

    protected $fillable = [
        '_key',
        'hash',
        'annotations',
        'issuer',
        'subtitle',
        'targetProgress',
        'title',
    ];

    public $timestamps = false;
    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'annotations' => 'array',
            'issuer' => 'array',
            'subtitle' => 'array',
            'targetProgress' => 'integer',
            'title' => 'array',
        ];
    }
}
