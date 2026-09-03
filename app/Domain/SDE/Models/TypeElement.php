<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class TypeElement extends Model
{
    protected $table = 'sde.type_elements';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'elements',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'elements' => 'array',
    ];
}
