<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agents_in_space', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('dungeonID');
            $table->unsignedBigInteger('solarSystemID');
            $table->unsignedBigInteger('spawnPointID');
            $table->unsignedBigInteger('typeID');
            $table->string('hash');
        });

        Schema::create('agent_types', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('name');
            $table->string('hash');
        });

        Schema::create('dynamic_item_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('attributeIDs');
            $table->json('inputOutputMapping');
            $table->string('hash');
        });

        Schema::create('graphics', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('graphicFile', 255)->nullable();
            $table->string('iconFolder', 255)->nullable();
            $table->unsignedBigInteger('sofMaterialSetID')->nullable();
            $table->string('sofFactionName', 100)->nullable();
            $table->string('sofHullName', 100)->nullable();
            $table->string('sofRaceName', 100)->nullable();
            $table->json('sofLayout')->nullable();
            $table->string('hash');
        });

        Schema::create('map_moons', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedInteger('solarSystemID')->index();
            $table->unsignedBigInteger('orbitID')->index();
            $table->unsignedBigInteger('typeID');
            $table->unsignedBigInteger('celestialIndex');
            $table->unsignedBigInteger('orbitIndex');
            $table->jsonb('uniqueName')->nullable();
            $table->float('radius');
            $table->jsonb('position');
            $table->json('attributes');
            $table->json('statistics')->nullable();
            $table->json('npcStationIDs')->nullable();
            $table->string('hash');
        });

        Schema::create('map_solar_systems', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->boolean('border')->nullable();
            $table->unsignedInteger('constellationID')->index();
            $table->boolean('hub')->nullable();
            $table->boolean('international')->nullable();
            $table->double('luminosity', 10, 6)->nullable();
            $table->unsignedBigInteger('factionID')->nullable();
            $table->jsonb('name');
            $table->jsonb('planetIDs')->nullable();
            $table->jsonb('position');
            $table->jsonb('position2D')->nullable();
            $table->bigInteger('radius')->nullable();
            $table->unsignedInteger('regionID')->index();
            $table->boolean('regional')->nullable();
            $table->string('securityClass', 5)->nullable();
            $table->double('securityStatus', 10, 6);
            $table->unsignedInteger('starID')->nullable()->index();
            $table->jsonb('stargateIDs')->nullable();
            $table->boolean('corridor')->nullable();
            $table->boolean('fringe')->nullable();
            $table->json('disallowedAnchorCategories')->nullable();
            $table->json('disallowedAnchorGroups')->nullable();
            $table->unsignedBigInteger('wormholeClassID')->nullable();
            $table->string('visualEffect', 100)->nullable();
            $table->string('hash');
        });

        Schema::create('npc_characters', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('bloodlineID');
            $table->boolean('ceo');
            $table->unsignedBigInteger('corporationID')->index();
            $table->boolean('gender');
            $table->unsignedBigInteger('locationID')->index()->nullable();
            $table->jsonb('name');
            $table->unsignedBigInteger('raceID');
            $table->dateTime('startDate')->nullable();
            $table->jsonb('agent')->nullable();
            $table->boolean('uniqueName');
            $table->jsonb('skills')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('ancestryID')->nullable();
            $table->unsignedBigInteger('careerID')->nullable();
            $table->unsignedBigInteger('schoolID')->nullable();
            $table->unsignedBigInteger('specialityID')->nullable();
            $table->string('hash');
        });

        Schema::create('planet_resources', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->integer('power')->nullable();
            $table->integer('workforce')->nullable();
            $table->jsonb('reagent')->nullable();
            $table->string('hash');
        });

        Schema::create('races', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('name');
            $table->jsonb('description')->nullable();
            $table->unsignedBigInteger('iconID')->nullable()->index();
            $table->unsignedInteger('shipTypeID')->nullable()->index();
            $table->jsonb('skills')->nullable();
            $table->string('hash');
        });

        Schema::create('skins', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->string('internalName');
            $table->unsignedBigInteger('skinMaterialID')->index();
            $table->jsonb('types');
            $table->boolean('allowCCPDevs');
            $table->jsonb('skinDescription')->nullable();
            $table->boolean('visibleSerenity');
            $table->boolean('visibleTranquility');
            $table->boolean('isStructureSkin')->nullable();
            $table->string('hash');
        });

        Schema::create('bloodlines', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('name');
            $table->jsonb('description');
            $table->unsignedTinyInteger('charisma');
            $table->unsignedTinyInteger('intelligence');
            $table->unsignedTinyInteger('memory');
            $table->unsignedTinyInteger('perception');
            $table->unsignedTinyInteger('willpower');
            $table->unsignedBigInteger('iconID')->nullable()->index();
            $table->unsignedBigInteger('corporationID')->index();
            $table->unsignedTinyInteger('raceID')->index();
            $table->string('hash');
        });

        Schema::create('blueprints', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('activities');
            $table->unsignedBigInteger('blueprintTypeID')->index();
            $table->unsignedBigInteger('maxProductionLimit');
            $table->string('hash');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('name');
            $table->unsignedBigInteger('iconID')->nullable();
            $table->boolean('published')->default(false);
            $table->string('hash');
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->jsonb('description');
            $table->unsignedBigInteger('groupID')->index();
            $table->jsonb('name');
            $table->jsonb('recommendedFor')->nullable();
            $table->jsonb('skillTypes');
            $table->string('hash');
        });

        Schema::create('character_attributes', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->text('description');
            $table->unsignedBigInteger('iconID')->nullable();
            $table->jsonb('name');
            $table->text('notes')->nullable();
            $table->text('shortDescription');
            $table->string('hash');
        });

        Schema::create('dogma_attribute_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->string('hash');
        });

        Schema::create('dogma_units', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('description')->nullable();
            $table->jsonb('displayName')->nullable();
            $table->string('name', 50);
            $table->string('hash');
        });

        Schema::create('freelance_job_schemas', function (Blueprint $table) {
            $table->string('_key')->primary();
            $table->jsonb('_value')->nullable();
            $table->string('hash');
        });

        Schema::create('icons', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->string('iconFile');
            $table->string('hash');
        });

        Schema::create('map_planets', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('uniqueName')->nullable();
            $table->integer('solarSystemID')->index();
            $table->integer('orbitID')->index();
            $table->integer('celestialIndex');
            $table->integer('typeID')->index();
            $table->bigInteger('radius');
            $table->json('position');
            $table->json('attributes');
            $table->json('statistics');
            $table->json('moonIDs')->nullable();
            $table->json('asteroidBeltIDs')->nullable();
            $table->json('npcStationIDs')->nullable();
            $table->string('hash');
        });

        Schema::create('map_regions', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('constellationIDs');
            $table->json('description')->nullable();
            $table->integer('factionID')->nullable()->index();
            $table->json('name');
            $table->integer('nebulaID')->nullable();
            $table->json('position');
            $table->integer('wormholeClassID')->nullable();
            $table->string('hash');
        });

        Schema::create('market_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('description')->nullable();
            $table->json('name');
            $table->boolean('hasTypes');
            $table->integer('iconID')->nullable();
            $table->integer('parentGroupID')->nullable()->index();
            $table->string('hash');
        });

        Schema::create('masteries', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('_value');
            $table->string('hash');
        });

        Schema::create('types', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('groupID')->nullable();
            $table->unsignedBigInteger('metaGroupID')->nullable();
            $table->unsignedBigInteger('factionID')->nullable();
            $table->json('name');
            $table->json('description')->nullable();
            $table->double('mass')->nullable();
            $table->unsignedBigInteger('portionSize')->nullable();
            $table->unsignedBigInteger('variationParentTypeID')->nullable();
            $table->boolean('published')->nullable();
            $table->double('volume')->nullable();
            $table->double('radius')->nullable();
            $table->unsignedInteger('graphicID')->nullable();
            $table->unsignedInteger('iconID')->nullable();
            $table->unsignedInteger('soundID')->nullable();
            $table->unsignedBigInteger('raceID')->nullable();
            $table->double('basePrice')->nullable();
            $table->double('capacity')->nullable();
            $table->unsignedBigInteger('marketGroupID')->nullable();
            $table->string('hash');
        });

        Schema::create('meta_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('name');
            $table->json('description')->nullable();
            $table->json('color')->nullable();
            $table->unsignedInteger('iconID')->nullable();
            $table->string('iconSuffix', 50)->nullable();
            $table->string('hash');
        });

        Schema::create('npc_corporation_divisions', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('internalName');
            $table->string('displayName')->nullable();
            $table->json('name');
            $table->json('leaderTypeName');
            $table->json('description')->nullable();
            $table->string('hash');
        });

        Schema::create('skin_materials', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('displayName')->nullable();
            $table->unsignedInteger('materialSetID');
            $table->string('hash');
        });

        Schema::create('station_operations', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('activityID');
            $table->json('operationName');
            $table->json('description')->nullable();
            $table->float('border');
            $table->float('corridor');
            $table->float('fringe');
            $table->float('hub');
            $table->float('ratio');
            $table->float('manufacturingFactor');
            $table->float('researchFactor');
            $table->json('services');
            $table->json('stationTypes')->nullable();
            $table->string('hash');
        });

        Schema::create('type_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('randomizedMaterials')->nullable();
            $table->json('materials')->nullable();
            $table->string('hash');
        });

        Schema::create('ancestries', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('bloodlineID');
            $table->unsignedTinyInteger('charisma');
            $table->unsignedTinyInteger('intelligence');
            $table->unsignedTinyInteger('memory');
            $table->unsignedTinyInteger('perception');
            $table->unsignedTinyInteger('willpower');
            $table->json('description');
            $table->unsignedInteger('iconID')->nullable();
            $table->json('name');
            $table->string('shortDescription', 255)->nullable();
            $table->string('hash');
        });

        Schema::create('contraband_types', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('factions');
            $table->string('hash');
        });

        Schema::create('control_tower_resources', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('resources');
            $table->string('hash');
        });

        Schema::create('corporation_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('name');
            $table->string('hash');
        });

        Schema::create('dbuff_collections', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->string('aggregateMode', 50);
            $table->string('developerDescription', 255)->nullable();
            $table->json('displayName')->nullable();
            $table->json('itemModifiers')->nullable();
            $table->json('locationGroupModifiers')->nullable();
            $table->json('locationModifiers')->nullable();
            $table->json('locationRequiredSkillModifiers')->nullable();
            $table->string('operationName', 50);
            $table->string('showOutputValueInUI', 50);
            $table->string('hash');
        });

        Schema::create('dogma_attributes', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->unsignedBigInteger('attributeCategoryID')->nullable();
            $table->unsignedTinyInteger('dataType');
            $table->float('defaultValue');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('chargeRechargeTimeID')->nullable();
            $table->json('displayName')->nullable();
            $table->boolean('displayWhenZero');
            $table->boolean('highIsGood');
            $table->unsignedInteger('iconID')->nullable();
            $table->string('name', 100);
            $table->boolean('published');
            $table->boolean('stackable');
            $table->json('tooltipDescription')->nullable();
            $table->json('tooltipTitle')->nullable();
            $table->unsignedBigInteger('unitID')->nullable();
            $table->unsignedBigInteger('maxAttributeID')->nullable();
            $table->unsignedBigInteger('minAttributeID')->nullable();
            $table->string('hash');
        });

        Schema::create('dogma_effects', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('description')->nullable();
            $table->unsignedBigInteger('iconID')->nullable();
            $table->unsignedInteger('dischargeAttributeID')->nullable();
            $table->boolean('disallowAutoRepeat');
            $table->unsignedTinyInteger('distribution')->nullable();
            $table->unsignedInteger('durationAttributeID')->nullable();
            $table->unsignedTinyInteger('effectCategoryID');
            $table->boolean('electronicChance');
            $table->unsignedInteger('falloffAttributeID')->nullable();
            $table->unsignedBigInteger('resistanceAttributeID')->nullable();
            $table->string('guid')->nullable();
            $table->boolean('isAssistance');
            $table->boolean('isOffensive');
            $table->boolean('isWarpSafe');
            $table->json('modifierInfo')->nullable();
            $table->jsonb('displayName')->nullable();
            $table->string('name');
            $table->boolean('propulsionChance');
            $table->boolean('published');
            $table->unsignedInteger('rangeAttributeID')->nullable();
            $table->boolean('rangeChance');
            $table->unsignedInteger('trackingSpeedAttributeID')->nullable();
            $table->unsignedBigInteger('fittingUsageChanceAttributeID')->nullable();
            $table->unsignedBigInteger('npcUsageChanceAttributeID')->nullable();
            $table->unsignedBigInteger('npcActivationChanceAttributeID')->nullable();
            $table->string('hash');
        });

        Schema::create('factions', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->unsignedInteger('corporationID')->nullable();
            $table->json('description')->nullable();
            $table->json('shortDescription')->nullable();
            $table->boolean('isUnique')->nullable();
            $table->integer('uniqueName')->nullable();
            $table->json('name');
            $table->unsignedInteger('solarSystemID')->nullable();
            $table->unsignedBigInteger('stationCount')->nullable();
            $table->unsignedBigInteger('stationSystemCount')->nullable();
            $table->string('flatLogo')->nullable();
            $table->string('flatLogoWithName')->nullable();
            $table->unsignedBigInteger('iconID')->nullable();
            $table->json('memberRaces')->nullable();
            $table->unsignedBigInteger('militiaCorporationID')->nullable();
            $table->unsignedBigInteger('sizeFactor')->nullable();
            $table->string('hash');
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->boolean('anchorable');
            $table->boolean('anchored');
            $table->unsignedInteger('categoryID');
            $table->boolean('fittableNonSingleton');
            $table->unsignedBigInteger('iconID')->nullable();
            $table->json('name');
            $table->boolean('published');
            $table->boolean('useBasePrice');
            $table->string('hash');
        });

        Schema::create('landmarks', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->unsignedBigInteger('iconID')->nullable();
            $table->json('description');
            $table->unsignedInteger('locationID')->nullable();
            $table->json('position')->nullable();
            $table->json('name');
            $table->string('hash');
        });

        Schema::create('map_asteroid_belts', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('celestialIndex');
            $table->unsignedBigInteger('orbitID');
            $table->unsignedBigInteger('orbitIndex');
            $table->jsonb('position');
            $table->double('radius')->nullable();
            $table->unsignedInteger('solarSystemID');
            $table->jsonb('statistics')->nullable();
            $table->jsonb('uniqueName')->nullable();
            $table->unsignedBigInteger('typeID');
            $table->string('hash');
        });

        Schema::create('map_constellations', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedInteger('factionID')->nullable();
            $table->json('name');
            $table->jsonb('position');
            $table->unsignedBigInteger('regionID');
            $table->json('solarSystemIDs');
            $table->unsignedBigInteger('wormholeClassID')->nullable();
            $table->string('hash');
        });

        Schema::create('map_stargates', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedInteger('destinationSolarSystemID')->nullable();
            $table->unsignedBigInteger('destinationStargateID')->nullable();
            $table->json('destination')->nullable();
            $table->jsonb('position');
            $table->unsignedInteger('solarSystemID');
            $table->unsignedInteger('typeID');
            $table->string('hash');
        });

        Schema::create('map_stars', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->double('radius');
            $table->unsignedInteger('solarSystemID');
            $table->jsonb('statistics')->nullable();
            $table->unsignedInteger('typeID');
            $table->string('hash');
        });

        Schema::create('npc_corporations', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->unsignedInteger('ceoID')->nullable();
            $table->unsignedBigInteger('enemyID')->nullable();
            $table->boolean('deleted');
            $table->json('description')->nullable();
            $table->jsonb('divisions')->nullable();
            $table->string('extent');
            $table->boolean('hasPlayerPersonnelManager');
            $table->unsignedInteger('initialPrice');
            $table->integer('memberLimit');
            $table->float('minSecurity');
            $table->integer('minimumJoinStanding');
            $table->json('name');
            $table->boolean('sendCharTerminationMessage');
            $table->jsonb('investors')->nullable();
            $table->unsignedBigInteger('shares');
            $table->string('size');
            $table->unsignedBigInteger('stationID')->nullable();
            $table->jsonb('exchangeRates')->nullable();
            $table->unsignedBigInteger('factionID')->nullable();
            $table->unsignedBigInteger('friendID')->nullable();
            $table->unsignedBigInteger('secondaryActivityID')->nullable();
            $table->float('taxRate');
            $table->string('tickerName');
            $table->boolean('uniqueName');
            $table->unsignedBigInteger('iconID')->nullable();
            $table->unsignedBigInteger('raceID')->nullable();
            $table->json('allowedMemberRaces')->nullable();
            $table->json('corporationTrades')->nullable();
            $table->json('lpOfferTables')->nullable();
            $table->float('sizeFactor')->nullable();
            $table->unsignedBigInteger('solarSystemID')->nullable();
            $table->unsignedBigInteger('mainActivityID')->nullable();

            $table->string('hash');
        });

        Schema::create('npc_stations', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('celestialIndex')->nullable();
            $table->unsignedBigInteger('operationID');
            $table->unsignedBigInteger('orbitID');
            $table->unsignedBigInteger('orbitIndex')->nullable();
            $table->unsignedInteger('ownerID');
            $table->jsonb('position');
            $table->float('reprocessingEfficiency');
            $table->unsignedBigInteger('reprocessingHangarFlag');
            $table->float('reprocessingStationsTake');
            $table->unsignedInteger('solarSystemID');
            $table->unsignedInteger('typeID');
            $table->boolean('useOperationName');
            $table->string('hash');
        });

        Schema::create('planet_schematics', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('cycleTime');
            $table->json('name');
            $table->json('pins');
            $table->json('types');
            $table->string('hash');
        });

        Schema::create('skin_licenses', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->integer('duration');
            $table->unsignedInteger('licenseTypeID');
            $table->unsignedInteger('skinID');
            $table->boolean('isSingleUse')->nullable();
            $table->string('hash');
        });

        Schema::create('sovereignty_upgrades', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('fuel')->nullable();
            $table->string('mutually_exclusive_group');
            $table->unsignedBigInteger('power_allocation')->nullable();
            $table->unsignedBigInteger('workforce_allocation')->nullable();
            $table->unsignedBigInteger('workforce_production')->nullable();
            $table->unsignedBigInteger('power_production')->nullable();
            $table->string('hash');
        });

        Schema::create('station_services', function (Blueprint $table) {
            $table->unsignedTinyInteger('_key')->primary();
            $table->json('serviceName');
            $table->json('description')->nullable();
            $table->string('hash');
        });

        Schema::create('translation_languages', function (Blueprint $table) {
            $table->string('_key', 5)->primary();
            $table->string('name');
            $table->string('hash');
        });

        Schema::create('type_bonuses', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('roleBonuses')->nullable();
            $table->json('types')->nullable();
            $table->unsignedBigInteger('iconID')->nullable();
            $table->json('miscBonuses')->nullable();
            $table->string('hash');
        });

        Schema::create('type_dogmas', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('dogmaAttributes');
            $table->json('dogmaEffects')->nullable();
            $table->string('hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents_in_space');
        Schema::dropIfExists('agent_types');
        Schema::dropIfExists('dynamic_item_attributes');
        Schema::dropIfExists('graphics');
        Schema::dropIfExists('map_moons');
        Schema::dropIfExists('map_solar_systems');
        Schema::dropIfExists('npc_characters');
        Schema::dropIfExists('planet_resources');
        Schema::dropIfExists('races');
        Schema::dropIfExists('skins');
        Schema::dropIfExists('bloodlines');
        Schema::dropIfExists('blueprints');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('dogma_attribute_categories');
        Schema::dropIfExists('dogma_units');
        Schema::dropIfExists('freelance_job_schemas');
        Schema::dropIfExists('icons');
        Schema::dropIfExists('map_planets');
        Schema::dropIfExists('map_regions');
        Schema::dropIfExists('market_groups');
        Schema::dropIfExists('masteries');
        Schema::dropIfExists('types');
        Schema::dropIfExists('type_materials');
        Schema::dropIfExists('station_operations');
        Schema::dropIfExists('skin_materials');
        Schema::dropIfExists('npc_corporation_divisions');
        Schema::dropIfExists('meta_groups');
        Schema::dropIfExists('landmarks');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('factions');
        Schema::dropIfExists('dogma_effects');
        Schema::dropIfExists('dogma_attributes');
        Schema::dropIfExists('dbuff_collections');
        Schema::dropIfExists('corporation_activities');
        Schema::dropIfExists('control_tower_resources');
        Schema::dropIfExists('contraband_types');
        Schema::dropIfExists('ancestries');
        Schema::dropIfExists('station_services');
        Schema::dropIfExists('sovereignty_upgrades');
        Schema::dropIfExists('skin_licenses');
        Schema::dropIfExists('planet_schematics');
        Schema::dropIfExists('npc_stations');
        Schema::dropIfExists('npc_corporations');
        Schema::dropIfExists('map_stars');
        Schema::dropIfExists('map_stargates');
        Schema::dropIfExists('map_constellations');
        Schema::dropIfExists('map_asteroid_belts');
        Schema::dropIfExists('type_dogmas');
        Schema::dropIfExists('type_bonuses');
        Schema::dropIfExists('translation_languages');
    }
};
