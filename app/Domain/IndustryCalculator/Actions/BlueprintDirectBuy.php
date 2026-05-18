<?php

namespace App\Domain\IndustryCalculator\Actions;

use App\Domain\Infrastructure\SDE\Models\Blueprint\Blueprint;

class BlueprintDirectBuy
{
    public function instaBuy(int $blueprintID)
    {
        $blueprint = Blueprint::query()
            ->with([
                'type.group',
                'manufacturing.products.type.group',
                'manufacturing.materials.type.group',
                'manufacturing.skills.type.group',
                'invention.products.type.group',
                'invention.materials.type.group',
                'invention.skills.type.group',
                'reaction.products.type.group',
                'reaction.materials.type.group',
                'reaction.skills.type.group',
            ])
            ->where('_key', $blueprintID)
            ->first();

        if (!$blueprint) {
            return response()->json(['error' => 'Blueprint not found'], 404);
        }

        $requiredSkills = [];

        // 1. Reusable component formatter (Base Metrics only)
        $formatComponent = function ($component) {
            return [
                'typeID'      => $component->typeID,
                'name'        => $component->type->name['en'] ?? 'Unknown Item',
                'iconID'      => $component->type->iconID ?? null,
                'groupID'     => $component->type->groupID ?? null,
                'group_name'  => $component->type->group->name['en'] ?? 'Unknown Group',
                'unit_volume' => $component->type->volume ?? 0,
                'unit_mass'   => $component->type->mass ?? 0,
                'quantity'    => $component->quantity ?? 1,

                ...(!empty($component->probability) ? ['probability' => $component->probability] : []),
            ];
        };

        // 2. Extractor helper to find highest skill prerequisites 
        $processSkills = function ($skills) use (&$requiredSkills) {
            foreach ($skills as $s) {
                $typeID = $s->typeID;
                if (!isset($requiredSkills[$typeID]) || $s->level > $requiredSkills[$typeID]['level']) {
                    $requiredSkills[$typeID] = [
                        'typeID'     => $typeID,
                        'name'       => $s->type->name['en'] ?? 'Unknown Skill',
                        'groupID'    => $s->type->groupID ?? null,
                        'group_name' => $s->type->group->name['en'] ?? 'Unknown Group',
                        'level'      => $s->level
                    ];
                }
            }
        };

        // 3. Activity profile mapper (Omits nested skills)
        $formatActivity = function ($activity) use ($formatComponent, $processSkills) {
            if (!$activity) return null;

            if (!empty($activity->skills)) {
                $processSkills($activity->skills);
            }

            return [
                'time'      => $activity->time,
                'products'  => collect($activity->products)->map(fn($p) => $formatComponent($p))->toArray(),
                'materials' => collect($activity->materials)->map(fn($m) => $formatComponent($m))->toArray(),
            ];
        };

        return [
            'blueprintTypeID'    => $blueprint->_key,
            'name'               => $blueprint->type->name['en'] ?? 'Unknown Blueprint',
            'iconID'             => $blueprint->type->iconID ?? null,
            'groupID'            => $blueprint->type->groupID ?? null,
            'group_name'         => $blueprint->type->group->name['en'] ?? 'Unknown Group',
            'maxProductionLimit' => $blueprint->maxProductionLimit,
            'copy_time'          => $blueprint->copy_time,
            'research_time'      => $blueprint->research_time,
            'material_time'      => $blueprint->material_time,
            'manufacturing'      => $formatActivity($blueprint->manufacturing),
            'invention'          => $formatActivity($blueprint->invention),
            'reaction'           => $formatActivity($blueprint->reaction),
            'skills'             => array_values($requiredSkills),
        ];
    }
}
