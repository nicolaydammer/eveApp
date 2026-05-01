<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeMaterial extends Model
{
    use HasFactory;

    protected $table = 'sde.type_materials';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'materials',
        'randomizedMaterials',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'materials' => 'array',
    ];
}
