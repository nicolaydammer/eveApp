<?php

namespace App\Domain\IndustryCalculator\Models;

use App\Domain\Auth\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndustryPlan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'plan',
        'name'
    ];

    protected $hidden = [
        'user_id'
    ];

    /**
     * Get the user that owns the industry plan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'plan' => 'array'
    ];
}
