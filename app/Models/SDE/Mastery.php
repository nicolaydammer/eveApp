<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mastery extends Model
{
    use HasFactory;

    protected $table = 'masteries';

    protected $primaryKey = '_key';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        '_value',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        '_value' => 'array',
    ];
}
