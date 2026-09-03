<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class SkillPlan extends Model
{
    protected $table = 'sde.skill_plans';

    protected $primaryKey = '_key';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        '_key',
        'careerPathID',
        'description',
        'factionID',
        'internalName',
        'milestones',
        'name',
        'npcCorporationDivision',
        'skillRequirements',
        'hash',
    ];

    protected function casts(): array
    {
        return [
            '_key' => 'integer',
            'careerPathID' => 'integer',
            'description' => 'array',
            'factionID' => 'integer',
            'milestones' => 'array',
            'name' => 'array',
            'npcCorporationDivision' => 'integer',
            'skillRequirements' => 'array',
        ];
    }
}
