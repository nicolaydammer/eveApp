<?php

namespace App\Domain\SDE\Jobs;

use App\Domain\Infrastructure\SDE\Models\Blueprint\Blueprint;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintInvention;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintInventionMaterial;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintInventionProduct;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintInventionSkill;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintManufacturing;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintManufacturingMaterial;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintManufacturingProduct;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintManufacturingSkill;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintReaction;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintReactionMaterial;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintReactionProduct;
use App\Domain\Infrastructure\SDE\Models\Blueprint\BlueprintReactionSkill;
use App\Domain\SDE\Jobs\AbstractSDEJob;
use App\Domain\SDE\Jobs\SDEJobInterface;
use App\Domain\SDE\Mapping\SDEModelResolver;
use Illuminate\Support\Facades\DB;

class ImportSDEBlueprint extends AbstractSDEJob implements SDEJobInterface
{
    public function handle(SDEModelResolver $SDEModelResolver): void
    {
        $blueprints = [];
        $mfgRows = [];
        $invRows = [];
        $reacRows = [];

        $blueprintKeys = [];

        // Phase 1: Parse and collect base records (deduplicated by blueprint key)
        foreach ($this->data as $sdeData) {
            $key = $sdeData['_key'];
            $blueprintKeys[] = $key;

            $blueprints[$key] = [
                '_key' => $key,
                'blueprintTypeID' => $sdeData['blueprintTypeID'],
                'maxProductionLimit' => $sdeData['maxProductionLimit'],
                'hash' => $sdeData['hash'],
                'copy_time' => $sdeData['activities']['copying']['time'] ?? 0,
                'material_time' => $sdeData['activities']['research_material']['time'] ?? 0,
                'research_time' => $sdeData['activities']['research_time']['time'] ?? 0,
            ];

            if (isset($sdeData['activities']['manufacturing'])) {
                $mfgRows[$key] = [
                    'blueprintID' => $key,
                    'time' => $sdeData['activities']['manufacturing']['time'] ?? 0,
                ];
            }

            if (isset($sdeData['activities']['invention'])) {
                $invRows[$key] = [
                    'blueprintID' => $key,
                    'time' => $sdeData['activities']['invention']['time'] ?? 0,
                ];
            }

            if (isset($sdeData['activities']['reaction'])) {
                $reacRows[$key] = [
                    'blueprintID' => $key,
                    'time' => $sdeData['activities']['reaction']['time'] ?? 0,
                ];
            }
        }

        // Parent Inserts
        DB::transaction(function () use ($blueprints, $mfgRows, $invRows, $reacRows) {
            if (!empty($blueprints)) {
                Blueprint::query()->upsert(array_values($blueprints), ['_key'], ['blueprintTypeID', 'maxProductionLimit', 'hash', 'copy_time', 'material_time', 'research_time']);
            }
            if (!empty($mfgRows)) {
                BlueprintManufacturing::query()->upsert(array_values($mfgRows), ['blueprintID'], ['time']);
            }
            if (!empty($invRows)) {
                BlueprintInvention::query()->upsert(array_values($invRows), ['blueprintID'], ['time']);
            }
            if (!empty($reacRows)) {
                BlueprintReaction::query()->upsert(array_values($reacRows), ['blueprintID'], ['time']);
            }
        });

        // Phase 2: Pull the auto-increment IDs for mapping
        $mfgIdMap = BlueprintManufacturing::query()
            ->whereIn('blueprintID', $blueprintKeys)
            ->pluck('id', 'blueprintID')
            ->toArray();

        $invIdMap = BlueprintInvention::query()
            ->whereIn('blueprintID', $blueprintKeys)
            ->pluck('id', 'blueprintID')
            ->toArray();

        $reacIdMap = BlueprintReaction::query()
            ->whereIn('blueprintID', $blueprintKeys)
            ->pluck('id', 'blueprintID')
            ->toArray();

        $mfgSkills = [];
        $mfgProducts = [];
        $mfgMaterials = [];

        $invSkills = [];
        $invProducts = [];
        $invMaterials = [];

        $reacSkills = [];
        $reacProducts = [];
        $reacMaterials = [];

        // Phase 3: Loop data and deduplicate sub-tables using composite array keys
        foreach ($this->data as $sdeData) {
            $key = $sdeData['_key'];

            // Process Manufacturing children
            if (isset($sdeData['activities']['manufacturing']) && isset($mfgIdMap[$key])) {
                $mfgId = $mfgIdMap[$key];
                $mfg = $sdeData['activities']['manufacturing'];

                foreach ($mfg['skills'] ?? [] as $row) {
                    $mfgSkills["{$mfgId}_{$row['typeID']}"] = [
                        'blueprints_manufacturing_id' => $mfgId,
                        'typeID' => $row['typeID'],
                        'level' => $row['level']
                    ];
                }
                foreach ($mfg['products'] ?? [] as $row) {
                    $mfgProducts["{$mfgId}_{$row['typeID']}"] = [
                        'blueprints_manufacturing_id' => $mfgId,
                        'typeID' => $row['typeID'],
                        'quantity' => $row['quantity']
                    ];
                }
                foreach ($mfg['materials'] ?? [] as $row) {
                    $mfgMaterials["{$mfgId}_{$row['typeID']}"] = [
                        'blueprints_manufacturing_id' => $mfgId,
                        'typeID' => $row['typeID'],
                        'quantity' => $row['quantity']
                    ];
                }
            }

            // Process Invention children
            if (isset($sdeData['activities']['invention']) && isset($invIdMap[$key])) {
                $invId = $invIdMap[$key];
                $inv = $sdeData['activities']['invention'];

                foreach ($inv['skills'] ?? [] as $row) {
                    $invSkills["{$invId}_{$row['typeID']}"] = [
                        'blueprints_invention_id' => $invId,
                        'typeID' => $row['typeID'],
                        'level' => $row['level']
                    ];
                }
                foreach ($inv['products'] ?? [] as $row) {
                    $invProducts["{$invId}_{$row['typeID']}"] = [
                        'blueprints_invention_id' => $invId,
                        'typeID' => $row['typeID'],
                        'quantity' => $row['quantity'],
                        'probability' => $row['probability'] ?? null
                    ];
                }
                foreach ($inv['materials'] ?? [] as $row) {
                    $invMaterials["{$invId}_{$row['typeID']}"] = [
                        'blueprints_invention_id' => $invId,
                        'typeID' => $row['typeID'],
                        'quantity' => $row['quantity']
                    ];
                }
            }

            // Process Reaction children
            if (isset($sdeData['activities']['reaction']) && isset($reacIdMap[$key])) {
                $reacId = $reacIdMap[$key];
                $reac = $sdeData['activities']['reaction'];

                foreach ($reac['skills'] ?? [] as $row) {
                    $reacSkills["{$reacId}_{$row['typeID']}"] = [
                        'blueprints_reaction_id' => $reacId,
                        'typeID' => $row['typeID'],
                        'level' => $row['level']
                    ];
                }
                foreach ($reac['products'] ?? [] as $row) {
                    $reacProducts["{$reacId}_{$row['typeID']}"] = [
                        'blueprints_reaction_id' => $reacId,
                        'typeID' => $row['typeID'],
                        'quantity' => $row['quantity'],
                    ];
                }
                foreach ($reac['materials'] ?? [] as $row) {
                    $reacMaterials["{$reacId}_{$row['typeID']}"] = [
                        'blueprints_reaction_id' => $reacId,
                        'typeID' => $row['typeID'],
                        'quantity' => $row['quantity']
                    ];
                }
            }
        }

        // Phase 4: Batch save clean, unique chunks
        DB::transaction(function () use (
            $mfgSkills,
            $mfgProducts,
            $mfgMaterials,
            $invSkills,
            $invProducts,
            $invMaterials,
            $reacSkills,
            $reacProducts,
            $reacMaterials
        ) {
            // Manufacturing Child Batches
            if (!empty($mfgSkills)) {
                BlueprintManufacturingSkill::query()->upsert(array_values($mfgSkills), ['blueprints_manufacturing_id', 'typeID'], ['level']);
            }
            if (!empty($mfgProducts)) {
                BlueprintManufacturingProduct::query()->upsert(array_values($mfgProducts), ['blueprints_manufacturing_id', 'typeID'], ['quantity']);
            }
            if (!empty($mfgMaterials)) {
                BlueprintManufacturingMaterial::query()->upsert(array_values($mfgMaterials), ['blueprints_manufacturing_id', 'typeID'], ['quantity']);
            }

            // Invention Child Batches
            if (!empty($invSkills)) {
                BlueprintInventionSkill::query()->upsert(array_values($invSkills), ['blueprints_invention_id', 'typeID'], ['level']);
            }
            if (!empty($invProducts)) {
                BlueprintInventionProduct::query()->upsert(array_values($invProducts), ['blueprints_invention_id', 'typeID'], ['quantity', 'probability']);
            }
            if (!empty($invMaterials)) {
                BlueprintInventionMaterial::query()->upsert(array_values($invMaterials), ['blueprints_invention_id', 'typeID'], ['quantity']);
            }

            // Reaction Child Batches
            if (!empty($reacSkills)) {
                BlueprintReactionSkill::query()->upsert(array_values($reacSkills), ['blueprints_reaction_id', 'typeID'], ['level']);
            }
            if (!empty($reacProducts)) {
                BlueprintReactionProduct::query()->upsert(array_values($reacProducts), ['blueprints_reaction_id', 'typeID'], ['quantity']);
            }
            if (!empty($reacMaterials)) {
                BlueprintReactionMaterial::query()->upsert(array_values($reacMaterials), ['blueprints_reaction_id', 'typeID'], ['quantity']);
            }
        });
    }
}
