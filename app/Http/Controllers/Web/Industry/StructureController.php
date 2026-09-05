<?php

namespace App\Http\Controllers\Web\Industry;

use App\Domain\SDE\Models\DogmaEffect;
use App\Domain\SDE\Models\IndustryModifierSource;
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

    public function getIndustryModifiers(Request $request): JsonResponse
    {
        $securityStatus = $request->float('securityStatus', 0.5);
        $rigIds = $request->input('rigIds', []);

        // dd($securityStatus, $rigIds);

        if (empty($rigIds)) {
            return response()->json([]);
        }

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
            ->unique();

        $effects = DogmaEffect::query()
            ->whereIn('_key', $effectIds)
            ->get([
                '_key',
                'modifierInfo',
            ])
            ->keyBy('_key');

        $modifiers = $rigs->map(function (TypeDogma $rig) use (
            $effects,
            $securityStatus,
        ) {
            $attributes = collect($rig->dogmaAttributes ?? []);

            /*
         * Get the security multiplier from this rig's SDE attributes.
         */
            $securityModifier = $this->getSecurityModifier(
                $attributes,
                $securityStatus,
            );

            /*
         * Apply the security modifier to the rig's
         * engineering bonus attributes.
         */
            $rigModifiers = collect([2593, 2594, 2595])
                ->mapWithKeys(function (int $attributeId) use (
                    $attributes,
                    $securityModifier,
                ) {
                    $value = $attributes
                        ->firstWhere('attributeID', $attributeId)['value'] ?? null;

                    if ($value === null) {
                        return [];
                    }

                    return [
                        $attributeId => $value * $securityModifier,
                    ];
                });

            /*
         * Resolve the rig's Dogma effects into the attributes
         * that those engineering bonuses actually modify.
         */
            $resolvedModifiers = collect($rig->dogmaEffects ?? [])
                ->map(fn(array $effect) => $effects->get($effect['effectID']))
                ->filter()
                ->flatMap(function (DogmaEffect $effect) use ($rigModifiers) {
                    return collect($effect->modifierInfo ?? [])
                        ->filter(function (array $modifier) use ($rigModifiers) {
                            return $rigModifiers->has(
                                $modifier['modifyingAttributeID'] ?? null
                            );
                        })
                        ->map(function (array $modifier) use ($rigModifiers) {
                            return [
                                'modifiedAttributeID' =>
                                $modifier['modifiedAttributeID'],

                                'value' => round(
                                    $rigModifiers->get(
                                        $modifier['modifyingAttributeID']
                                    ),
                                    10
                                ),

                                'operation' =>
                                $modifier['operation'],
                            ];
                        });
                })
                ->values();

            return [
                '_key' => $rig->_key,
                'modifiers' => $resolvedModifiers,
            ];
        });

        return response()->json($modifiers);
    }

    private function getSecurityModifier(
        Collection $attributes,
        float $securityStatus,
    ): float {
        $attributeId = match (true) {
            $securityStatus >= 0.5 => 2355,
            $securityStatus > 0.0 => 2356,
            default => 2357,
        };

        return (float) (
            $attributes->firstWhere('attributeID', $attributeId)['value'] ?? 1
        );
    }
}
