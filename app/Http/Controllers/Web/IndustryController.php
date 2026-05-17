<?php

namespace App\Http\Controllers\Web;


use App\Domain\Infrastructure\SDE\Models\Blueprint\Blueprint;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintManufacturingProduct;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintReactionProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndustryController
{
    public function index()
    {
        return $this->getTree(23912);

        // return $this->instaBuy(23912);
    }

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

        // 1. Reusable helper with calculated metrics & group lookups
        $formatComponent = function ($component, $isSkill = false) {
            // Skills don't have a quantity (they use level), so default multiplier is 1
            $quantity = $isSkill ? 1 : ($component->quantity ?? 1);

            $unitVolume = $component->type->volume ?? 0;
            $unitMass   = $component->type->mass ?? 0;

            return [
                'typeID'       => $component->typeID,
                'name'         => $component->type->name['en'] ?? 'Unknown Item',
                'iconID'       => $component->type->iconID ?? null,
                'groupID'      => $component->type->groupID ?? null,
                'group_name'   => $component->type->group->name['en'] ?? 'Unknown Group',
                'unit_volume'  => $unitVolume,
                'total_volume' => $unitVolume * $quantity,
                'unit_mass'    => $unitMass,
                'total_mass'   => $unitMass * $quantity,

                ...($isSkill
                    ? ['level' => $component->level]
                    : ['quantity' => $quantity]
                ),
                ...(!empty($component->probability) ? ['probability' => $component->probability] : []),
            ];
        };

        // 2. Reusable helper to format the activities
        $formatActivity = function ($activity) use ($formatComponent) {
            if (!$activity) return null;

            return [
                'time'      => $activity->time,
                'products'  => collect($activity->products)->map(fn($p) => $formatComponent($p))->toArray(),
                'materials' => collect($activity->materials)->map(fn($m) => $formatComponent($m))->toArray(),
                'skills'    => collect($activity->skills)->map(fn($s) => $formatComponent($s, true))->toArray(),
            ];
        };

        // 3. Build the streamlined final array
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
        ];
    }

    public function getTree(int $blueprintTypeID)
    {
        // 1. Fetch Root Blueprint (Includes Manufacturing or Reaction definitions)
        $rootBlueprint = Blueprint::query()
            ->with([
                'type.group',
                'manufacturing.materials.type.group',
                'manufacturing.products.type.group',
                'manufacturing.skills.type.group',
                'reaction.materials.type.group',
                'reaction.products.type.group',
                'reaction.skills.type.group'
            ])
            ->where('_key', $blueprintTypeID)
            ->first();

        if (!$rootBlueprint) {
            return response()->json(['error' => 'Blueprint formula entry not found'], 404);
        }

        // Active root reference can be manufacturing OR reaction
        $rootActivity = $rootBlueprint->manufacturing ?? $rootBlueprint->reaction;
        $initialMaterials = $rootActivity?->materials ?? [];

        // 2. Unified BFS Phase: Collect ALL blueprint and reaction definitions in batches
        $blueprintPool = [];
        $queue = collect($initialMaterials)->pluck('typeID')->toArray();
        $processed = [];

        while (!empty($queue)) {
            $toFetch = array_diff($queue, $processed, array_keys($blueprintPool));
            if (empty($toFetch)) {
                break;
            }

            $processed = array_merge($processed, $toFetch);
            $nextLayerQueue = [];

            // A. Search standard manufacturing outputs
            $mfgProducts = BlueprintManufacturingProduct::query()
                ->whereIn('typeID', $toFetch)
                ->with([
                    'manufacturing.blueprint.type.group',
                    'manufacturing.materials.type.group',
                    'manufacturing.products.type.group',
                    'manufacturing.skills.type.group'
                ])->get();

            foreach ($mfgProducts as $product) {
                $bp = $product->manufacturing?->blueprint;
                if ($bp) {
                    $blueprintPool[$product->typeID] = ['activity_type' => 'manufacturing', 'model' => $bp];
                    foreach ($bp->manufacturing?->materials ?? [] as $mat) {
                        $nextLayerQueue[] = $mat->typeID;
                    }
                }
            }

            // B. Search reaction outputs (chemical compositions, polymers, etc.)
            $reactionProducts = BlueprintReactionProduct::query()
                ->whereIn('typeID', $toFetch)
                ->with([
                    'reaction.blueprint.type.group',
                    'reaction.materials.type.group',
                    'reaction.products.type.group',
                    'reaction.skills.type.group'
                ])->get();

            foreach ($reactionProducts as $product) {
                $bp = $product->reaction?->blueprint;
                if ($bp) {
                    $blueprintPool[$product->typeID] = ['activity_type' => 'reaction', 'model' => $bp];
                    foreach ($bp->reaction?->materials ?? [] as $mat) {
                        $nextLayerQueue[] = $mat->typeID;
                    }
                }
            }

            $queue = array_unique($nextLayerQueue);
        }

        // 3. Recursive In-Memory Formatter
        $formatTreeNode = function ($material, $multiplier = 1) use (&$formatTreeNode, $blueprintPool) {
            $typeID = $material->typeID;
            $qtyNeeded = $material->quantity * $multiplier;

            $unitVolume = $material->type->volume ?? 0;
            $unitMass = $material->type->mass ?? 0;

            $node = [
                'typeID'       => $typeID,
                'name'         => $material->type->name['en'] ?? 'Unknown Item',
                'iconID'       => $material->type->iconID ?? null,
                'groupID'      => $material->type->groupID ?? null,
                'group_name'   => $material->type->group->name['en'] ?? 'Unknown Group',
                'unit_volume'  => $unitVolume,
                'total_volume' => $unitVolume * $qtyNeeded,
                'unit_mass'    => $unitMass,
                'total_mass'   => $unitMass * $qtyNeeded,
                'quantity'     => $qtyNeeded,
                'formula'      => null
            ];

            // Resolve how sub-component items get built (Manufacturing or Reaction)
            if (isset($blueprintPool[$typeID])) {
                $poolEntry = $blueprintPool[$typeID];
                $subBp = $poolEntry['model'];
                $actType = $poolEntry['activity_type']; // 'manufacturing' or 'reaction'

                $activityData = $subBp->{$actType};
                $productRow = collect($activityData->products)->firstWhere('typeID', $typeID);

                $portionSize = $productRow ? $productRow->quantity : 1;
                $runsNeeded = ceil($qtyNeeded / $portionSize);

                $node['formula'] = [
                    'type'            => $actType, // Identifies "manufacturing" vs "reaction" on frontend
                    'blueprintTypeID' => $subBp->_key,
                    'name'            => $subBp->type->name['en'] ?? 'Unknown Blueprint',
                    'iconID'          => $subBp->type->iconID ?? null,
                    'groupID'         => $subBp->type->groupID ?? null,
                    'group_name'      => $subBp->type->group->name['en'] ?? 'Unknown Group',
                    'runs_required'   => $runsNeeded,
                    'total_time'      => ($activityData->time ?? 0) * $runsNeeded,
                    'materials'       => collect($activityData->materials ?? [])
                        ->map(fn($m) => $formatTreeNode($m, $runsNeeded))
                        ->toArray(),
                    'skills'          => collect($activityData->skills ?? [])
                        ->map(fn($s) => [
                            'typeID'     => $s->typeID,
                            'name'       => $s->type->name['en'] ?? 'Unknown Skill',
                            'groupID'    => $s->type->groupID ?? null,
                            'group_name' => $s->type->group->name['en'] ?? 'Unknown Group',
                            'level'      => $s->level
                        ])->toArray()
                ];
            }

            return $node;
        };

        // 4. Compile Root Object Output
        $rootType = $rootBlueprint->manufacturing ? 'manufacturing' : ($rootBlueprint->reaction ? 'reaction' : 'unknown');

        $finalTree = [
            'blueprintTypeID'    => $rootBlueprint->_key,
            'name'               => $rootBlueprint->type->name['en'] ?? 'Unknown Blueprint',
            'iconID'             => $rootBlueprint->type->iconID ?? null,
            'groupID'            => $rootBlueprint->type->groupID ?? null,
            'group_name'         => $rootBlueprint->type->group->name['en'] ?? 'Unknown Group',
            'maxProductionLimit' => $rootBlueprint->maxProductionLimit,
            'copy_time'          => $rootBlueprint->copy_time,
            'research_time'      => $rootBlueprint->research_time,
            'material_time'      => $rootBlueprint->material_time,
            'activity_type'      => $rootType,
            'requirements'       => [
                'time'      => $rootActivity->time ?? 0,
                'products'  => collect($rootActivity?->products ?? [])->map(fn($p) => [
                    'typeID'     => $p->typeID,
                    'name'       => $p->type->name['en'] ?? 'Unknown Item',
                    'groupID'    => $p->type->groupID ?? null,
                    'group_name' => $p->type->group->name['en'] ?? 'Unknown Group',
                    'quantity'   => $p->quantity
                ])->toArray(),
                'materials' => collect($initialMaterials)->map(fn($m) => $formatTreeNode($m, 1))->toArray(),
                'skills'    => collect($rootActivity?->skills ?? [])->map(fn($s) => [
                    'typeID'     => $s->typeID,
                    'name'       => $s->type->name['en'] ?? 'Unknown Skill',
                    'groupID'    => $s->type->groupID ?? null,
                    'group_name' => $s->type->group->name['en'] ?? 'Unknown Group',
                    'level'      => $s->level
                ])->toArray(),
            ]
        ];

        return response()->json($finalTree);
    }
}
