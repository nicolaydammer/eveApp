<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelanceJobSchema extends Model
{
    use HasFactory;

    protected $table = 'freelance_job_schemas';

    protected $fillable = [
        'key',
        'content_tags',
        'title',
        'description',
        'progress_description',
        'reward_description',
        'target_description',
        'max_contributions',
        'parameters',
        'icon_id',
    ];

    protected $casts = [
        'content_tags' => 'array',
        'title' => 'array',
        'description' => 'array',
        'progress_description' => 'array',
        'reward_description' => 'array',
        'target_description' => 'array',
        'max_contributions' => 'array',
        'parameters' => 'array',
    ];

    protected $primaryKey = 'key';

    public $timestamps = true;

    public $incrementing = false;

    protected $keyType = 'string';
}
