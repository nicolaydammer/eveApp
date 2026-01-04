<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessSDEData implements ShouldQueue
{
    use Queueable;

    private $models = [
        'agentTypes' => 'AgentType',
        'agentsInSpace' => 'AgentInSpace',
        'ancestries' => 'Ancestry',
        'bloodlines' => 'Bloodline',
        'blueprints' => 'Blueprint',
        'categories' => 'Category',
        'certificates' => 'Certificate',
        'characterAttributes' => 'CharacterAttribute',
        'contrabandTypes' => 'ContrabandType',
        'controlTowerResources' => 'ControlTowerResource',
        'corporationActivities' => 'CorporationActivity',
        'dbuffCollections' => 'DbuffCollection',
        'dogmaAttributeCategories' => 'DogmaAttributeCategory',
        'dogmaAttributes' => 'DogmaAttribute',
        'dogmaEffects' => 'DogmaEffect',
        'dogmaUnits' => 'DogmaUnit',
        'dynamicItemAttributes' => 'DynamicItemAttribute',
        'factions' => 'Faction',
        'freelanceJobSchemas' => 'FreelanceJobSchema',
        'graphics' => 'Graphic',
        'groups' => 'Group',
        'icons' => 'Icon',
        'landmarks' => 'Landmark',
        'mapAsteroidBelts' => 'MapAsteroidBelt',
        'mapConstellations' => 'MapConstellation',
        'mapMoons' => 'MapMoon',
        'mapPlanets' => 'MapPlanet',
        'mapRegions' => 'MapRegion',
        'mapSolarSystems' => 'MapSolarSystem',
        'mapStargates' => 'MapStargate',
        'mapStars' => 'MapStar',
        'marketGroups' => 'MarketGroup',
        'masteries' => 'Mastery',
        'metaGroups' => 'MetaGroup',
        'npcCharacters' => 'NpcCharacter',
        'npcCorporationDivisions' => 'NpcCorporationDivision',
        'npcCorporations' => 'NpcCorporation',
        'npcStations' => 'NpcStation',
        'planetResources' => 'PlanetResource',
        'planetSchematics' => 'PlanetSchematic',
        'races' => 'Race',
        'skinLicenses' => 'SkinLicense',
        'skinMaterials' => 'SkinMaterial',
        'skins' => 'Skin',
        'sovereigntyUpgrades' => 'SovereigntyUpgrade',
        'stationOperations' => 'StationOperation',
        'stationServices' => 'StationService',
        'translationLanguages' => 'TranslationLanguage',
        'typeBonus' => 'TypeBonus',
        'typeDogma' => 'TypeDogma',
        'typeMaterials' => 'TypeMaterial',
        'types' => 'Type',
    ];

    /**
     * Create a new job instance.
     */
    public function __construct(private string $modelName, private array $data, private bool $firstTime = false) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // get model name
        $modelName = substr($this->modelName, 0, -6);

        if (! isset($this->models[$modelName])) {
            throw new \LogicException(
                "Unsupported SDE file '{$this->modelName}'. ".
                'This application is version-locked and requires a migration to support new SDE versions.'
            );
        }

        $modelName = $this->models[$modelName];
        $modelClass = "App\\Models\\SDE\\{$modelName}";
        $modelClassInstance = new $modelClass;
        $table = $modelClassInstance->getTable();
        $fillables = $modelClassInstance->getFillable();
        $unsetKeys = [];

        // make all keys populated
        $allKeys = [];
        foreach ($this->data as $row) {
            $allKeys = array_unique(array_merge($allKeys, array_keys($row)));
        }

        foreach ($this->data as &$row) {
            foreach ($allKeys as $key) {
                if (! array_key_exists($key, $row)) {
                    $row[$key] = null;
                }
            }

            if (! $this->firstTime) {
                // throw out stuff if its not needed for an update
                $dbData = DB::table($table)->select('hash')->where('_key', '=', $row['_key'])->get();
                if (count($dbData) == 1 && $dbData->first()->hash == $row['hash']) {
                    $unsetKeys[] = $row['_key'];
                }
            }
        }

        if (! $this->firstTime) {
            $unsetKeys = array_flip($unsetKeys);

            $this->data = array_filter(
                $this->data,
                fn ($row) => ! isset($unsetKeys[$row['_key']])
            );
        }

        foreach ($this->data as &$row) { // Use reference so changes apply directly
            array_walk($row, function (&$value, $key) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
            });
        }
        unset($row);

        if (! empty($this->data)) {
            $modelClass::query()->upsert($this->data, ['_key'], $fillables);
        }
    }
}
