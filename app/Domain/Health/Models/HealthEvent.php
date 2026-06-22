<?php

namespace App\Domain\Health\Models;

use App\Domain\Health\Enums\HealthSource;
use Illuminate\Database\Eloquent\Model;

class HealthEvent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'source',
        'exception',
        'context',
        'occurrences',
        'first_seen_at',
        'last_seen_at',
    ];

    /**
     * The model's attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'source' => HealthSource::class,
            'context' => 'array',
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }
}
