<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    use HasFactory;

    protected $table = 'icons';

    protected $fillable = [
        'iconFile',
    ];

    protected $casts = [
        // No casts needed as all fields are primitives
    ];

    protected $primaryKey = '_key';

    public $timestamps = true;

    public $incrementing = false;

    protected $keyType = 'integer';
}
