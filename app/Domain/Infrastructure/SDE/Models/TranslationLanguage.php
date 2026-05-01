<?php

namespace App\Domain\Infrastructure\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationLanguage extends Model
{
    protected $table = 'sde.translation_languages';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        '_key',
        'name',
        'hash',
    ];

    protected $casts = [];
}
