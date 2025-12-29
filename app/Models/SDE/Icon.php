<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    use HasFactory;

    protected $table = 'icons';

    protected $fillable = [
        '_key',
        'iconFile',
        'hash',
    ];

    protected $casts = [
        // No casts needed as all fields are primitives
    ];

    protected $primaryKey = '_key';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'integer';
}
