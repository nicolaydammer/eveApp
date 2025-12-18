<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class TypeBonus extends Model
{
    protected $table = 'type_bonuses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    // Cast array columns
    protected $casts = [
        'roleBonuses' => 'array',
        'types' => 'array',
        'miscBonuses' => 'array',
    ];
}
