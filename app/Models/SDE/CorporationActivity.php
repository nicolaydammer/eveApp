<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporationActivity extends Model
{
    use HasFactory;

    protected $table = 'corporation_activities';
    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'name',
    ];

    protected $casts = [
        '_key' => 'integer',
        'name' => 'array',
    ];
}
