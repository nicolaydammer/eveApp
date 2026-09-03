<?php

namespace App\Domain\SDE\Models;

use Illuminate\Database\Eloquent\Model;

class MilitaryCampaignObjective extends Model
{
    protected $table = 'sde.military_campaign_objectives';

    protected $fillable = [
        '_key',
        'hash',
        'annotations',
        'campaignID',
        'careerPath',
        'contentTags',
        'contributionMethodConfiguration',
        'issuer',
        'maxProgressPerParticipant',
        'presentingCharacterID',
        'rewards',
        'subtitle',
        'targetProgress',
        'title',
    ];

    public $timestamps = false;
    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'annotations' => 'array',
            'contentTags' => 'array',
            'contributionMethodConfiguration' => 'array',
            'issuer' => 'array',
            'maxProgressPerParticipant' => 'integer',
            'presentingCharacterID' => 'integer',
            'rewards' => 'array',
            'subtitle' => 'array',
            'targetProgress' => 'integer',
            'title' => 'array',
        ];
    }
}
