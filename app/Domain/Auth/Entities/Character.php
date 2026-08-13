<?php

namespace App\Domain\Auth\Entities;

use App\Domain\Infrastructure\Esi\Enums\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Character extends Model
{
    use HasFactory;

    protected $primaryKey = 'CharacterID';

    public $incrementing = false;

    protected $fillable = [
        'CharacterID',
        'user_id',
        'CharacterName',
        'accessToken',
        'refreshToken',
        'expires_at',
        'scopes'
    ];

    protected $hidden = [
        'accessToken',
        'refreshToken',
        'expires_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected $casts = [
        'expires_at' => 'datetime',
        'accessToken' => 'encrypted',
        'refreshToken' => 'encrypted',
        'scopes' => 'array'
    ];

    public function hasScope(Scope $scope): bool
    {
        return in_array(
            $scope->value,
            $this->scopes ?? [],
            true
        );
    }

    protected static function newFactory()
    {
        return \Database\Factories\CharacterFactory::new();
    }
}
