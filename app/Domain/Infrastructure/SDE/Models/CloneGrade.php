<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class CloneGrade extends Model
{
    protected $table = 'sde.clone_grades';

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
