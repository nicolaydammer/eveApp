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
use App\Domain\SDE\Mapping\SDEModelResolver;

class ImportSDEBlueprint extends AbstractSDEJob implements SDEJobInterface
{
    public function __construct(string $modelName, array $data, bool $firstTime)
    {
        return parent::__construct($modelName, $data, $firstTime);
    }

    public function handle(SDEModelResolver $SDEModelResolver): void
    {
        if (true) {
            // plain insert stuff        

            foreach ($this->data as $sdeData) {
                $blueprint = new Blueprint();
                $blueprint->_key = $sdeData['_key'];
                $blueprint->blueprintTypeID = $sdeData['blueprintTypeID'];
                $blueprint->maxProductionLimit = $sdeData['maxProductionLimit'];
                $blueprint->hash = $sdeData['hash'];
                $blueprint->copy_time = $sdeData['activities']['copying']['time'] ?? 0;
                $blueprint->material_time = $sdeData['activities']['research_material']['time'] ?? 0;
                $blueprint->research_time = $sdeData['activities']['research_time']['time'] ?? 0;
                $blueprint->save();

                if (isset($sdeData['activities']['manufacturing'])) {
                    $blueprintManufacturing = new BlueprintManufacturing();
                    $blueprintManufacturing->blueprintID = $blueprint->_key;
                    $blueprintManufacturing->time = $sdeData['activities']['manufacturing']['time'];
                    $blueprintManufacturing->save();

                    foreach ($sdeData['activities']['manufacturing'] as $type => $manufacturingData) {
                        //loop over skills
                        if ($type == 'skills') {
                            foreach ($manufacturingData as $manufacturingSkillData) {
                                $skill = new BlueprintManufacturingSkill();
                                $skill->level = $manufacturingSkillData['level'];
                                $skill->typeID = $manufacturingSkillData['typeID'];
                                $skill->blueprints_manufacturing_id = $blueprintManufacturing->id;
                                $skill->save();
                            }
                        }
                        //loop over products
                        if ($type == 'products') {
                            foreach ($manufacturingData as $manufacturingProductData) {
                                $product = new BlueprintManufacturingProduct();
                                $product->blueprints_manufacturing_id = $blueprintManufacturing->id;
                                $product->typeID = $manufacturingProductData['typeID'];
                                $product->quantity = $manufacturingProductData['quantity'];
                                $product->save();
                            }
                        }
                        if ($type == 'materials') {
                            //loop over materials
                            foreach ($manufacturingData as $manufacturingMaterialData) {
                                $material = new BlueprintManufacturingMaterial();
                                $material->blueprints_manufacturing_id = $blueprintManufacturing->id;
                                $material->typeID = $manufacturingMaterialData['typeID'];
                                $material->quantity = $manufacturingMaterialData['quantity'];
                                $material->save();
                            }
                        }
                    }
                }

                if (isset($sdeData['activities']['invention'])) {
                    $blueprintInvention = new BlueprintInvention();
                    $blueprintInvention->blueprintID = $blueprint->_key;
                    $blueprintInvention->time = $sdeData['activities']['invention']['time'];
                    $blueprintInvention->save();

                    foreach ($sdeData['activities']['invention'] as $type => $inventionData) {
                        //loop over skills
                        if ($type == 'skills') {
                            foreach ($inventionData as $inventionSkillData) {
                                $skill = new BlueprintInventionSkill();
                                $skill->level = $inventionSkillData['level'];
                                $skill->typeID = $inventionSkillData['typeID'];
                                $skill->blueprints_invention_id = $blueprintInvention->id;
                                $skill->save();
                            }
                        }
                        //loop over products
                        if ($type == 'products') {
                            foreach ($inventionData as $inventionProductData) {
                                $product = new BlueprintInventionProduct();
                                $product->blueprints_invention_id = $blueprintInvention->id;
                                $product->typeID = $inventionProductData['typeID'];
                                $product->quantity = $inventionProductData['quantity'];
                                $product->probability = $inventionProductData['probability'] ?? null;
                                $product->save();
                            }
                        }
                        //loop over materials
                        if ($type == 'materials') {
                            foreach ($inventionData as $inventionMaterialData) {
                                $material = new BlueprintInventionMaterial();
                                $material->blueprints_invention_id = $blueprintInvention->id;
                                $material->typeID = $inventionMaterialData['typeID'];
                                $material->quantity = $inventionMaterialData['quantity'];
                                $material->save();
                            }
                        }
                    }
                }
            }
        } else {
            $data = collect($this->data);
            $hashes = $data->pluck('hash')->toArray();
            $keys = $data->pluck('_key')->toArray();

            $blueprints = Blueprint::query()
                ->whereIn('_key', $keys)
                ->with(['manufacturing.material', 'manufacturing.products', 'manufacturing.skills', 'invention.material', 'invention.products', 'invention.skills'])
                ->get()->keyBy('_key');

            foreach ($this->data as $value) {

                if ($blueprint = $blueprints[$value['_key']]) {

                    if (in_array($blueprint->hash, $hashes)) {
                        continue;
                    }

                    $blueprint->blueprintTypeID = $value['blueprintTypeID'];
                    $blueprint->maxProductionLimit = $value['maxProductionLimit'];
                    // $blueprint->hash = $value['hash'];
                    $blueprint->copy_time = $value['activities']['copying']['time'] ?? 0;
                    $blueprint->material_time = $value['activities']['research_material']['time'] ?? 0;
                    $blueprint->research_time = $value['activities']['research_time']['time'] ?? 0;

                    $blueprint->save();

                    if ($value['activities']['manufacturing']) {
                        $manufacturing = $blueprint->manufacturing;

                        if ($manufacturing) {
                            $manufacturing->time = $value['activities']['manufacturing']['time'];
                            $manufacturing->save();
                        }
                    }

                    if ($value['activities']['invention']) {
                    }
                }
            }
        }
    }
}
