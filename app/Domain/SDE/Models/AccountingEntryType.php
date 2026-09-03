<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingEntryType extends Model
{
    protected $table = 'sde.accounting_entry_types';

    protected $fillable = [
        '_key',
        'hash',
        'description',
        'internalName',
        'journalMessage',
        'name',
    ];

    public $timestamps = false;
    public $incrementing = false;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'description' => 'array',
            'journalMessage' => 'array',
            'name' => 'array',
        ];
    }
}
