<?php

namespace App\Models\SDE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkinMaterial extends Model
{
    use HasFactory;

    protected $table = 'skin_materials';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        '_key',
        'displayName',
        'materialSetID',
        'hash',
    ];

    protected $casts = [
        '_key' => 'integer',
        'displayName' => 'array',
        'materialSetID' => 'integer',
    ];
}
