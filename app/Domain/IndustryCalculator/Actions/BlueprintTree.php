<?php

namespace App\Domain\IndustryCalculator\Actions;

use App\Domain\SDE\Models\Blueprint\Blueprint;
use App\Domain\SDE\Models\Blueprint\BlueprintManufacturingProduct;
use App\Domain\SDE\Models\Blueprint\BlueprintReactionProduct;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BlueprintTree
{
    public function getTree(int $blueprintTypeID): array
    {
        // 1. Fetch Root Formula Record
        $rootBlueprint = $this->getRootBlueprint($blueprintTypeID);

        $rootActivity = $rootBlueprint->manufacturing ?? $rootBlueprint->reaction;
        $initialMaterials = $rootActivity?->materials ?? [];

        // 2. BFS Batch Loading with Component & Reaction Caching Strategy
        $dependencies = $this->resolveDependencies($initialMaterials);


        // 3. Normalize Processing Graph (Extract Formulas & Skills)
        $requiredSkills = [];

        foreach ($rootActivity?->skills ?? [] as $s) {
            $this->recordSkills([
                'typeID' => $s->typeID,
                'name' => $s->type->name['en'] ?? 'Unknown Skill',
                'groupID' => $s->type->groupID ?? null,
                'group_name' => $s->type->group->name['en'] ?? 'Unknown Group',
                'level' => $s->level
            ], $requiredSkills);
        }

        $formulas = $this->generateFormulas($dependencies, $requiredSkills);

        // 4. Construct Structured Top-Level Tree Response
        $rootType = $rootBlueprint->manufacturing ? 'manufacturing' : ($rootBlueprint->reaction ? 'reaction' : 'unknown');

        return [
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
                'materials' => collect($initialMaterials)->map(fn($m) => [
                    'typeID'      => $m->typeID,
                    'name'        => $m->type->name['en'] ?? 'Unknown Item',
                    'iconID'      => $m->type->iconID ?? null,
                    'groupID'     => $m->type->groupID ?? null,
                    'group_name'  => $m->type->group->name['en'] ?? 'Unknown Group',
                    'unit_volume' => $m->type->volume ?? 0,
                    'unit_mass'   => $m->type->mass ?? 0,
                    'quantity'    => $m->quantity,
                    'formula_id'  => isset($dependencies[$m->typeID]) ? $dependencies[$m->typeID]['blueprintTypeID'] : null
                ])->toArray(),
            ],
            'formulas' => (object)$formulas,
            'skills'   => array_values($requiredSkills)
        ];
    }

    private function getRootBlueprint(int $blueprintTypeID): Blueprint
    {
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
            throw new Exception('Blueprint not found');
        }

        return $rootBlueprint;
    }

    private function resolveDependencies(Collection $initialMaterials): array
    {
        $blueprintPool = [];
        $queue = collect($initialMaterials)->pluck('typeID')->toArray();
        $processed = [];

        while (!empty($queue)) {
            $toFetch = array_diff($queue, $processed, array_keys($blueprintPool));
            if (empty($toFetch)) {
                break;
            }
            $processed = array_merge($processed, $toFetch);

            $missedTypeIDs = [];
            foreach ($toFetch as $typeID) {
                // Check cache first to avoid repeating lookups across any blueprint construction run
                $cacheKey = "sde_industry_formula_v1_{$typeID}";
                $cached = Cache::tags(config('cacheTags.industry_blueprints'))->get($cacheKey);
                if ($cached) {
                    $blueprintPool[$typeID] = $cached;
                } else {
                    $missedTypeIDs[] = $typeID;
                }
            }

            if (!empty($missedTypeIDs)) {
                // Bulk query manufacturing targets
                $mfgProducts = BlueprintManufacturingProduct::query()
                    ->whereIn('typeID', $missedTypeIDs)
                    ->with([
                        'manufacturing.blueprint.type.group',
                        'manufacturing.materials.type.group',
                        'manufacturing.products.type.group',
                        'manufacturing.skills.type.group'
                    ])->get();

                $this->buildFormulaEntry($mfgProducts, 'manufacturing', $blueprintPool);


                // Bulk query chemical/polymer reaction targets
                $reactionProducts = BlueprintReactionProduct::query()
                    ->whereIn('typeID', $missedTypeIDs)
                    ->with([
                        'reaction.blueprint.type.group',
                        'reaction.materials.type.group',
                        'reaction.products.type.group',
                        'reaction.skills.type.group'
                    ])->get();

                $this->buildFormulaEntry($reactionProducts, 'reaction', $blueprintPool);
            }

            // Gather dependencies from both cache hits and fresh queries for next layer tracking
            $nextLayerQueue = [];
            foreach ($toFetch as $typeID) {
                if (isset($blueprintPool[$typeID])) {
                    foreach ($blueprintPool[$typeID]['materials'] as $mat) {
                        $nextLayerQueue[] = $mat['typeID'];
                    }
                }
            }
            $queue = array_unique($nextLayerQueue);
        }

        return $blueprintPool;
    }

    private function buildFormulaEntry(Collection $products, string $activityType, array &$blueprintPool): void
    {
        foreach ($products as $product) {

            $bp = $product->$activityType?->blueprint;

            if (!$bp) {
                continue;
            }

            $activity = $bp->$activityType;

            $poolEntry = [
                'activity_type'   => $activityType,
                'blueprintTypeID' => $bp->_key,
                'name'            => $bp->type->name['en'] ?? 'Unknown Blueprint',
                'iconID'          => $bp->type->iconID ?? null,
                'groupID'         => $bp->type->groupID ?? null,
                'group_name'      => $bp->type->group->name['en'] ?? 'Unknown Group',
                'time'            => $activity->time ?? 0,
                'products'        => collect($activity->products)->map(fn($p) => ['typeID' => $p->typeID, 'quantity' => $p->quantity])->toArray(),
                'materials'       => collect($activity->materials)->map(fn($m) => [
                    'typeID' => $m->typeID,
                    'name' => $m->type->name['en'] ?? 'Unknown Item',
                    'iconID' => $m->type->iconID ?? null,
                    'groupID' => $m->type->groupID ?? null,
                    'group_name' => $m->type->group->name['en'] ?? 'Unknown Group',
                    'unit_volume' => $m->type->volume ?? 0,
                    'unit_mass' => $m->type->mass ?? 0,
                    'quantity' => $m->quantity,
                ])->toArray(),
                'skills'          => collect($activity->skills)->map(fn($s) => [
                    'typeID' => $s->typeID,
                    'name' => $s->type->name['en'] ?? 'Unknown Skill',
                    'groupID' => $s->type->groupID ?? null,
                    'group_name' => $s->type->group->name['en'] ?? 'Unknown Group',
                    'level' => $s->level,
                ])->toArray(),
            ];

            $blueprintPool[$product->typeID] = $poolEntry;
            Cache::tags(config('cacheTags.industry_blueprints'))->put("sde_industry_formula_v1_{$product->typeID}", $poolEntry, now()->addDays(7));
        }
    }

    public function recordSkills(array $skill, array &$requiredSkills): void
    {
        $typeID = $skill['typeID'];
        if (!isset($requiredSkills[$typeID]) || $skill['level'] > $requiredSkills[$typeID]['level']) {
            $requiredSkills[$typeID] = [
                'typeID'     => $typeID,
                'name'       => $skill['name'],
                'groupID'    => $skill['groupID'],
                'group_name' => $skill['group_name'],
                'level'      => $skill['level']
            ];
        }
    }

    public function generateFormulas(array $dependencies, array &$requiredSkills): array
    {
        $formulas = [];
        // Extract sub-blueprint formulas and skills into specialized lookup indices
        foreach ($dependencies as $typeID => $entry) {
            $bpID = $entry['blueprintTypeID'];

            foreach ($entry['skills'] as $s) {
                $this->recordSkills($s, $requiredSkills);
            }

            if (!isset($formulas[$bpID])) {
                $formulas[$bpID] = [
                    'blueprintTypeID' => $bpID,
                    'name'            => $entry['name'],
                    'iconID'          => $entry['iconID'],
                    'groupID'         => $entry['groupID'],
                    'group_name'      => $entry['group_name'],
                    'activity_type'   => $entry['activity_type'],
                    'time'            => $entry['time'],
                    'products'        => $entry['products'],
                    'materials'       => collect($entry['materials'])->map(fn($m) => [
                        'typeID'      => $m['typeID'],
                        'name'        => $m['name'],
                        'iconID'      => $m['iconID'],
                        'groupID'     => $m['groupID'],
                        'group_name'  => $m['group_name'],
                        'unit_volume' => $m['unit_volume'], // Base metric only
                        'unit_mass'   => $m['unit_mass'],   // Base metric only
                        'quantity'    => $m['quantity'],
                        'formula_id'  => isset($dependencies[$m['typeID']]) ? $dependencies[$m['typeID']]['blueprintTypeID'] : null // Reference ID connection point
                    ])->toArray()
                ];
            }
        }

        return $formulas;
    }
}
