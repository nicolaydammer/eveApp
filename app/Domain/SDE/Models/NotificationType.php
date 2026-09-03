<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $table = 'sde.notification_types';

    protected $primaryKey = '_key';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'internalName',
        'description',
        'journalMessage',
        'displayName',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'description' => 'array',
            'journalMessage' => 'array',
            'displayName' => 'array',
        ];
    }
}
