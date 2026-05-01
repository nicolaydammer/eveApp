<?php

namespace App\Domain\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    protected $primaryKey = 'CharacterID';

    public $incrementing = false;

    protected $fillable = [
        'CharacterID',
        'user_id',
        'CharacterName',
        'accessToken',
        'refreshToken',
        'expires_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
