<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryActivity extends Model
{
    protected $table = 'sde.industry_activities';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'description',
        'name',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
        ];
    }
}
