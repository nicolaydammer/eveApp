<?php

namespace App\Http\Controllers\Web;


use App\Domain\Infrastructure\SDE\Models\Blueprint\Blueprint;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintManufacturingProduct;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintReactionProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class IndustryController
{
    public function index()
    {
        // return $this->getTree(11568);
        return $this->getTree(23912);


        // return $this->instaBuy(23912);
        // return $this->instaBuy(11568);

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

        return response()->json([
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
            'skills'             => array_values($requiredSkills), // 🚀 Flat unique skill list
        ]);
    }

    public function getTree(int $blueprintTypeID)
    {
        // 1. Fetch Root Formula Record
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

        $rootActivity = $rootBlueprint->manufacturing ?? $rootBlueprint->reaction;
        $initialMaterials = $rootActivity?->materials ?? [];

        // 2. BFS Batch Loading with Component & Reaction Caching Strategy
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
                $cached = Cache::get($cacheKey);
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

                foreach ($mfgProducts as $product) {
                    $bp = $product->manufacturing?->blueprint;
                    if ($bp) {
                        $mfg = $bp->manufacturing;
                        $poolEntry = [
                            'activity_type'   => 'manufacturing',
                            'blueprintTypeID' => $bp->_key,
                            'name'            => $bp->type->name['en'] ?? 'Unknown Blueprint',
                            'iconID'          => $bp->type->iconID ?? null,
                            'groupID'         => $bp->type->groupID ?? null,
                            'group_name'      => $bp->type->group->name['en'] ?? 'Unknown Group',
                            'time'            => $mfg->time ?? 0,
                            'products'        => collect($mfg->products)->map(fn($p) => ['typeID' => $p->typeID, 'quantity' => $p->quantity])->toArray(),
                            'materials'       => collect($mfg->materials)->map(fn($m) => [
                                'typeID' => $m->typeID,
                                'name' => $m->type->name['en'] ?? 'Unknown Item',
                                'iconID' => $m->type->iconID ?? null,
                                'groupID' => $m->type->groupID ?? null,
                                'group_name' => $m->type->group->name['en'] ?? 'Unknown Group',
                                'unit_volume' => $m->type->volume ?? 0,
                                'unit_mass' => $m->type->mass ?? 0,
                                'quantity' => $m->quantity,
                            ])->toArray(),
                            'skills'          => collect($mfg->skills)->map(fn($s) => [
                                'typeID' => $s->typeID,
                                'name' => $s->type->name['en'] ?? 'Unknown Skill',
                                'groupID' => $s->type->groupID ?? null,
                                'group_name' => $s->type->group->name['en'] ?? 'Unknown Group',
                                'level' => $s->level,
                            ])->toArray(),
                        ];

                        $blueprintPool[$product->typeID] = $poolEntry;
                        Cache::put("sde_industry_formula_v1_{$product->typeID}", $poolEntry, now()->addDays(7));
                    }
                }

                // Bulk query chemical/polymer reaction targets
                $reactionProducts = BlueprintReactionProduct::query()
                    ->whereIn('typeID', $missedTypeIDs)
                    ->with([
                        'reaction.blueprint.type.group',
                        'reaction.materials.type.group',
                        'reaction.products.type.group',
                        'reaction.skills.type.group'
                    ])->get();

                foreach ($reactionProducts as $product) {
                    $bp = $product->reaction?->blueprint;
                    if ($bp) {
                        $react = $bp->reaction;
                        $poolEntry = [
                            'activity_type'   => 'reaction',
                            'blueprintTypeID' => $bp->_key,
                            'name'            => $bp->type->name['en'] ?? 'Unknown Blueprint',
                            'iconID'          => $bp->type->iconID ?? null,
                            'groupID'         => $bp->type->groupID ?? null,
                            'group_name'      => $bp->type->group->name['en'] ?? 'Unknown Group',
                            'time'            => $react->time ?? 0,
                            'products'        => collect($react->products)->map(fn($p) => ['typeID' => $p->typeID, 'quantity' => $p->quantity])->toArray(),
                            'materials'       => collect($react->materials)->map(fn($m) => [
                                'typeID' => $m->typeID,
                                'name' => $m->type->name['en'] ?? 'Unknown Item',
                                'iconID' => $m->type->iconID ?? null,
                                'groupID' => $m->type->groupID ?? null,
                                'group_name' => $m->type->group->name['en'] ?? 'Unknown Group',
                                'unit_volume' => $m->type->volume ?? 0,
                                'unit_mass' => $m->type->mass ?? 0,
                                'quantity' => $m->quantity,
                            ])->toArray(),
                            'skills'          => collect($react->skills)->map(fn($s) => [
                                'typeID' => $s->typeID,
                                'name' => $s->type->name['en'] ?? 'Unknown Skill',
                                'groupID' => $s->type->groupID ?? null,
                                'group_name' => $s->type->group->name['en'] ?? 'Unknown Group',
                                'level' => $s->level,
                            ])->toArray(),
                        ];

                        $blueprintPool[$product->typeID] = $poolEntry;
                        Cache::put("sde_industry_formula_v1_{$product->typeID}", $poolEntry, now()->addDays(7));
                    }
                }
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

        // 3. Normalize Processing Graph (Extract Formulas & Skills)
        $formulas = [];
        $requiredSkills = [];

        $recordSkill = function ($skill) use (&$requiredSkills) {
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
        };

        // Extract root blueprints skills
        foreach ($rootActivity?->skills ?? [] as $s) {
            $recordSkill([
                'typeID' => $s->typeID,
                'name' => $s->type->name['en'] ?? 'Unknown Skill',
                'groupID' => $s->type->groupID ?? null,
                'group_name' => $s->type->group->name['en'] ?? 'Unknown Group',
                'level' => $s->level
            ]);
        }

        // Extract sub-blueprint formulas and skills into specialized lookup indices
        foreach ($blueprintPool as $typeID => $entry) {
            $bpID = $entry['blueprintTypeID'];

            foreach ($entry['skills'] as $s) {
                $recordSkill($s);
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
                        'formula_id'  => isset($blueprintPool[$m['typeID']]) ? $blueprintPool[$m['typeID']]['blueprintTypeID'] : null // Reference ID connection point
                    ])->toArray()
                ];
            }
        }

        // 4. Construct Structured Top-Level Tree Response
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
                'materials' => collect($initialMaterials)->map(fn($m) => [
                    'typeID'      => $m->typeID,
                    'name'        => $m->type->name['en'] ?? 'Unknown Item',
                    'iconID'      => $m->type->iconID ?? null,
                    'groupID'     => $m->type->groupID ?? null,
                    'group_name'  => $m->type->group->name['en'] ?? 'Unknown Group',
                    'unit_volume' => $m->type->volume ?? 0,
                    'unit_mass'   => $m->type->mass ?? 0,
                    'quantity'    => $m->quantity,
                    'formula_id'  => isset($blueprintPool[$m->typeID]) ? $blueprintPool[$m->typeID]['blueprintTypeID'] : null
                ])->toArray(),
            ],
            'formulas' => (object)$formulas,            // 🚀 Flat relational lookup table map
            'skills'   => array_values($requiredSkills) // 🚀 Top-level distinct high-level prerequisites array
        ];

        return response()->json($finalTree);
    }
}
