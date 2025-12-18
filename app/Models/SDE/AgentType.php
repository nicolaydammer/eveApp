<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Model;

class AgentType extends Model
{
    protected $table = 'agent_types';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'name'
    ];
}
