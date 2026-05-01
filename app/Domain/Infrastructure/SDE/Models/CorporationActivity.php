<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporationActivity extends Model
{
    use HasFactory;

    protected $table = 'sde.corporation_activities';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'name',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'name' => 'array',
    ];
}
