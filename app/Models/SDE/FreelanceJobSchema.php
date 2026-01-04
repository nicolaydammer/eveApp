<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelanceJobSchema extends Model
{
    use HasFactory;

    protected $table = 'freelance_job_schemas';

    protected $fillable = [
        '_key',
        '_value',
        'hash',
    ];

    protected $casts = [
        '_value' => 'array',
    ];

    protected $primaryKey = '_key';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'integer';
}
