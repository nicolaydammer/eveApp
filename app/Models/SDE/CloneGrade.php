<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class CloneGrade extends Model
{
    protected $table = 'clone_grades';

    protected $primaryKey = '_key';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'name',
        'skills',
        'hash',
    ];

    protected $casts = [
        'skills' => 'array',
    ];
}
