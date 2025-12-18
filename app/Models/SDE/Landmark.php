<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landmark extends Model
{
    use HasFactory;

    protected $table = 'landmarks';
    protected $primaryKey = '_key';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'description',
        'locationID',
        'name',
    ];

    protected $casts = [
        '_key' => 'integer',
        'description' => 'array',
        'locationID' => 'integer',
        'name' => 'array',
    ];
}
