<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlTowerResource extends Model
{
    use HasFactory;

    protected $table = 'control_tower_resources';
    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'resources',
    ];

    protected $casts = [
        '_key' => 'integer',
        'resources' => 'array',
    ];
}
