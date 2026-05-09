<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blueprint extends Model
{
    protected $table = 'sde.blueprints';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'activities',
        'blueprintTypeID',
        'maxProductionLimit',
        'hash',
    ];

    protected $casts = [
        'activities' => 'array',
    ];

    protected $hidden = [
        'hash'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'blueprintTypeID', '_key');
    }
}
