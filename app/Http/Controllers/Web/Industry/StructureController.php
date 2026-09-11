<?php

namespace App\Http\Controllers\Web\Industry;

use App\Domain\SDE\Models\DogmaEffect;
use App\Domain\SDE\Models\IndustryModifierSource;
use App\Domain\SDE\Models\IndustryTargetFilter;
use App\Domain\SDE\Models\Type;
use App\Domain\SDE\Models\TypeDogma;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StructureController
{
    public function listStructuresByActivity(Request $request)
    {
        $activity = $request->integer('activity');

        if ($activity === 0) {
            return response()->json([]);
        }

        $activityField = match ($activity) {
            1 => 'manufacturing',
            3 => 'researchTime',
            4 => 'researchMaterial',
            5 => 'copying',
            8 => 'invention',
            9 => 'reaction',
            default => null,
        };

        if ($activityField === null) {
            return response()->json([]);
        }

        $sources = IndustryModifierSource::query()
            ->whereNotNull($activityField)
            ->pluck('_key');

        $structures = Type::query()
            ->whereIn('_key', $sources)
            ->whereIn('groupID', [
                1404, // Engineering Complex
                1406, // Refinery
                1657, // Citadel
            ])
            ->orderByRaw('"name"->>\'en\' ASC')
            ->get([
                '_key',
                'name',
                'groupID',
            ]);

        /*
     * Load dogma for all structures in one query.
     */
        $structureDogma = TypeDogma::query()
            ->whereIn('_key', $structures->pluck('_key'))
            ->get([
                '_key',
                'dogmaAttributes',
            ])
            ->keyBy('_key');

        return response()->json(
            $structures->map(function (Type $type) use ($structureDogma) {
                $dogma = $structureDogma->get($type->_key);

                $rigSlots = collect($dogma?->dogmaAttributes ?? [])
                    ->firstWhere('attributeID', 1137)['value'] ?? 0;

                return [
                    '_key' => $type->_key,
                    'name' => $type->name['en'] ?? null,
                    'groupId' => $type->groupID,
                    'rigSlots' => (int) $rigSlots,
                ];
            })
        );
    }

    public function listRigsByStructureAndActivity(Request $request)
    {
        $structureId = $request->integer('structureId');
        $activity = $request->integer('activity');

        if ($structureId === 0 || $activity === 0) {
            return response()->json([]);
        }

        $activityField = match ($activity) {
            1 => 'manufacturing',
            3 => 'researchTime',
            4 => 'researchMaterial',
            5 => 'copying',
            8 => 'invention',
            9 => 'reaction',
            default => null,
        };

        if ($activityField === null) {
            return response()->json([]);
        }

        $structure = Type::query()
            ->where('_key', $structureId)
            ->first([
                '_key',
                'groupID',
            ]);

        if (!$structure) {
            return response()->json([]);
        }

        /*
     * Get the structure's rig size.
     */
        $structureDogma = TypeDogma::query()
            ->where('_key', $structure->_key)
            ->first([
                'dogmaAttributes',
            ]);

        $structureRigSize = collect($structureDogma?->dogmaAttributes ?? [])
            ->firstWhere('attributeID', 1547)['value'] ?? null;

        if ($structureRigSize === null) {
            return response()->json([]);
        }

        /*
     * Get all rigs that:
     *
     * - fit the structure group
     * - match the structure rig size
     */
        $rigTypeKeys = TypeDogma::query()
            ->whereRaw(
                'EXISTS (
                SELECT 1
                FROM json_array_elements("dogmaAttributes") AS attribute
                WHERE (attribute->>\'attributeID\')::integer IN (1298, 1299, 1300)
                  AND (attribute->>\'value\')::numeric = ?
            )',
                [$structure->groupID]
            )
            ->whereRaw(
                'EXISTS (
                SELECT 1
                FROM json_array_elements("dogmaAttributes") AS attribute
                WHERE (attribute->>\'attributeID\')::integer = 1547
                  AND (attribute->>\'value\')::numeric = ?
            )',
                [$structureRigSize]
            )
            ->pluck('_key');

        $modifierSources = IndustryModifierSource::query()
            ->whereNotNull($activityField)
            ->pluck('_key');

        $rigs = Type::query()
            ->whereIn('_key', $modifierSources)
            ->whereIn('_key', $rigTypeKeys)
            ->orderByRaw('"name"->>\'en\' ASC')
            ->get([
                '_key',
                'name',
                'groupID',
            ]);

        return response()->json(
            $rigs->map(fn(Type $type) => [
                '_key' => $type->_key,
                'name' => $type->name['en'] ?? null,
                'groupID' => $type->groupID,
            ])
        );
    }

    public function getIndustryModifiers(Request $request)
    {
        $securityStatus = $request->float('securityStatus', 0.5);
        $activity = strtolower($request->string('activity')->toString());
        $rigIds = $request->input('rigIds', []);

        if (empty($rigIds)) {
            return [];
        }

        $securityAttribute = match (true) {
            $securityStatus >= 0.5 => 2355,
            $securityStatus > 0.0 => 2356,
            default => 2357,
        };

        $rigs = TypeDogma::query()
            ->whereIn('_key', $rigIds)
            ->get([
                '_key',
                'dogmaAttributes',
                'dogmaEffects',
            ]);

        $effectIds = $rigs
            ->flatMap(fn(TypeDogma $rig) => $rig->dogmaEffects ?? [])
            ->pluck('effectID')
            ->filter()
            ->unique()
            ->values();

        $effects = DogmaEffect::query()
            ->whereIn('_key', $effectIds)
            ->get([
                '_key',
                'modifierInfo',
            ]);

        $filters = IndustryTargetFilter::query()->get();

        return $rigs->map(function (TypeDogma $rig) use (
            $activity,
            $securityAttribute,
            $effects,
            $filters,
        ) {
            $dogmaAttributes = collect($rig->dogmaAttributes ?? [])
                ->keyBy(fn(array $attribute) => $attribute['attributeID']);

            $securityModifier = (float) (
                $dogmaAttributes->get($securityAttribute)['value'] ?? 1
            );

            $activitySource = IndustryModifierSource::query()
                ->where('_key', $rig->_key)
                ->first();

            $activityModifiers = $activitySource?->getAttribute($activity) ?? [];

            $modifiers = collect($rig->dogmaEffects ?? [])
                ->flatMap(function (array $rigEffect) use (
                    $effects,
                    $dogmaAttributes,
                    $securityModifier,
                    $activityModifiers,
                    $filters,
                ) {
                    $effectId = $rigEffect['effectID'] ?? null;

                    if ($effectId === null) {
                        return [];
                    }

                    $effect = $effects->first(
                        fn(DogmaEffect $effect) =>
                        (int) $effect->_key === (int) $effectId
                    );

                    if (!$effect) {
                        return [];
                    }

                    return collect($effect->modifierInfo ?? [])
                        ->filter(function (array $modifier) {
                            return isset(
                                $modifier['modifiedAttributeID']
                            );
                        })
                        ->map(function (array $modifier) use (
                            $dogmaAttributes,
                            $securityModifier,
                            $activityModifiers,
                            $filters,
                        ) {
                            $modifiedAttributeID =
                                $modifier['modifiedAttributeID'];

                            $modifierType = null;
                            $filterEntry = null;

                            foreach ($activityModifiers as $type => $entries) {
                                $entry = collect($entries)->first(
                                    fn(array $entry) =>
                                    (int) ($entry['dogmaAttributeID'] ?? 0) ===
                                        (int) $modifiedAttributeID
                                );

                                if ($entry) {
                                    $modifierType = $type;
                                    $filterEntry = $entry;

                                    break;
                                }
                            }

                            if ($modifierType === null) {
                                return null;
                            }

                            $modifyingAttributeID =
                                $modifier['modifyingAttributeID'] ?? null;

                            if ($modifyingAttributeID === null) {
                                return null;
                            }

                            $bonus = $dogmaAttributes
                                ->get($modifyingAttributeID)['value'] ?? null;

                            if ($bonus === null) {
                                return null;
                            }

                            $filterId = $filterEntry['filterID'] ?? null;

                            $filter = $filterId !== null
                                ? $filters->first(
                                    fn(IndustryTargetFilter $filter) =>
                                    (int) $filter->_key === (int) $filterId
                                )
                                : null;

                            return [
                                'attribute' => $modifierType,
                                'modifiedAttributeID' => $modifiedAttributeID,
                                'value' => round(
                                    (float) $bonus * $securityModifier,
                                    10
                                ),
                                'operation' => $modifier['operation'] ?? null,
                                'filter' => $filter ? [
                                    '_key' => $filter->_key,
                                    'categoryIDs' => $filter->categoryIDs,
                                    'groupIDs' => $filter->groupIDs,
                                    'name' => $filter->name,
                                ] : null,
                            ];
                        })
                        ->filter();
                })
                ->values()
                ->all();

            return [
                '_key' => $rig->_key,
                'modifiers' => $modifiers,
            ];
        })->values()->all();
    }
}
