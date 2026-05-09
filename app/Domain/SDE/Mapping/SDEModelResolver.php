<?php

namespace App\Domain\SDE\Mapping;

class SDEModelResolver
{
    protected $models = [
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
        'mercenaryTacticalOperations' => 'MercenaryTacticalOperation',
        'mapSecondarySuns' => 'MapSecondarySun',
        'cloneGrades' => 'CloneGrade',
        'compressibleTypes' => 'CompressibleType',
        'dungeons' => 'Dungeon',
        'typeLists' => 'TypeList',
        'archetypes' => 'ArcheType'
    ];

    public function __construct() {}

    // todo: pub static function of SDE file -> Model name resolving.
    public function resolveModelName(string $sdeFileName): string
    {
        return $this->models[$sdeFileName] ?? throw new \Exception("No model found for SDE file: $sdeFileName");
    }

    public function getAll(): array
    {
        return $this->models;
    }
}
