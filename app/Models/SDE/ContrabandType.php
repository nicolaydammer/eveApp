<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContrabandType extends Model
{
    use HasFactory;

    protected $table = 'contraband_types';
    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'factions',
    ];

    protected $casts = [
        '_key' => 'integer',
        'factions' => 'array',
    ];
}
