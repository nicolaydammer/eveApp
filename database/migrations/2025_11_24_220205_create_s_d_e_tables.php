<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sde.agents_in_space', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('dungeonID');
            $table->unsignedBigInteger('solarSystemID');
            $table->unsignedBigInteger('spawnPointID');
            $table->unsignedBigInteger('typeID');
            $table->string('hash');
        });

        Schema::create('sde.agent_types', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('name');
            $table->string('hash');
        });

        Schema::create('sde.dynamic_item_attributes', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('attributeIDs');
            $table->json('inputOutputMapping');
            $table->string('hash');
        });

        Schema::create('sde.graphics', function (Blueprint $table) {
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

        Schema::create('sde.map_moons', function (Blueprint $table) {
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

        Schema::create('sde.map_solar_systems', function (Blueprint $table) {
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

        Schema::create('sde.npc_characters', function (Blueprint $table) {
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

        Schema::create('sde.planet_resources', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->integer('power')->nullable();
            $table->integer('workforce')->nullable();
            $table->jsonb('reagent')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.races', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('name');
            $table->jsonb('description')->nullable();
            $table->unsignedBigInteger('iconID')->nullable()->index();
            $table->unsignedInteger('shipTypeID')->nullable()->index();
            $table->jsonb('skills')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.skins', function (Blueprint $table) {
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

        Schema::create('sde.bloodlines', function (Blueprint $table) {
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

        Schema::create('sde.blueprints', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('activities');
            $table->unsignedBigInteger('blueprintTypeID')->index();
            $table->unsignedBigInteger('maxProductionLimit');
            $table->string('hash');
        });

        Schema::create('sde.categories', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('name');
            $table->unsignedBigInteger('iconID')->nullable();
            $table->boolean('published')->default(false);
            $table->string('hash');
        });

        Schema::create('sde.certificates', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->jsonb('description');
            $table->unsignedBigInteger('groupID')->index();
            $table->jsonb('name');
            $table->jsonb('recommendedFor')->nullable();
            $table->jsonb('skillTypes');
            $table->string('hash');
        });

        Schema::create('sde.character_attributes', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->text('description');
            $table->unsignedBigInteger('iconID')->nullable();
            $table->jsonb('name');
            $table->text('notes')->nullable();
            $table->text('shortDescription');
            $table->string('hash');
        });

        Schema::create('sde.dogma_attribute_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.dogma_units', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('description')->nullable();
            $table->jsonb('displayName')->nullable();
            $table->string('name', 50);
            $table->string('hash');
        });

        Schema::create('sde.freelance_job_schemas', function (Blueprint $table) {
            $table->string('_key')->primary();
            $table->jsonb('_value')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.icons', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->string('iconFile');
            $table->string('hash');
        });

        Schema::create('sde.map_planets', function (Blueprint $table) {
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

        Schema::create('sde.map_regions', function (Blueprint $table) {
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

        Schema::create('sde.market_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('description')->nullable();
            $table->json('name');
            $table->boolean('hasTypes');
            $table->integer('iconID')->nullable();
            $table->integer('parentGroupID')->nullable()->index();
            $table->string('hash');
        });

        Schema::create('sde.masteries', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('_value');
            $table->string('hash');
        });

        Schema::create('sde.types', function (Blueprint $table) {
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

        Schema::create('sde.meta_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('name');
            $table->json('description')->nullable();
            $table->json('color')->nullable();
            $table->unsignedInteger('iconID')->nullable();
            $table->string('iconSuffix', 50)->nullable();
            $table->string('hash');
        });

        Schema::create('sde.npc_corporation_divisions', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('internalName');
            $table->string('displayName')->nullable();
            $table->json('name');
            $table->json('leaderTypeName');
            $table->json('description')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.skin_materials', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('displayName')->nullable();
            $table->unsignedInteger('materialSetID');
            $table->string('hash');
        });

        Schema::create('sde.station_operations', function (Blueprint $table) {
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

        Schema::create('sde.type_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('randomizedMaterials')->nullable();
            $table->json('materials')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.ancestries', function (Blueprint $table) {
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

        Schema::create('sde.contraband_types', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('factions');
            $table->string('hash');
        });

        Schema::create('sde.control_tower_resources', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('resources');
            $table->string('hash');
        });

        Schema::create('sde.corporation_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('name');
            $table->string('hash');
        });

        Schema::create('sde.dbuff_collections', function (Blueprint $table) {
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

        Schema::create('sde.dogma_attributes', function (Blueprint $table) {
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

        Schema::create('sde.dogma_effects', function (Blueprint $table) {
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

        Schema::create('sde.factions', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->unsignedInteger('corporationID')->nullable();
            $table->json('description')->nullable();
            $table->json('shortDescription')->nullable();
            $table->integer('uniqueName')->nullable();
            $table->json('name');
            $table->unsignedInteger('solarSystemID')->nullable();
            $table->string('flatLogo')->nullable();
            $table->string('flatLogoWithName')->nullable();
            $table->unsignedBigInteger('iconID')->nullable();
            $table->json('memberRaces')->nullable();
            $table->unsignedBigInteger('militiaCorporationID')->nullable();
            $table->unsignedBigInteger('sizeFactor')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.groups', function (Blueprint $table) {
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

        Schema::create('sde.landmarks', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->unsignedBigInteger('iconID')->nullable();
            $table->json('description');
            $table->unsignedInteger('locationID')->nullable();
            $table->json('position')->nullable();
            $table->json('name');
            $table->string('hash');
        });

        Schema::create('sde.map_asteroid_belts', function (Blueprint $table) {
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

        Schema::create('sde.map_constellations', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedInteger('factionID')->nullable();
            $table->json('name');
            $table->jsonb('position');
            $table->unsignedBigInteger('regionID');
            $table->json('solarSystemIDs');
            $table->unsignedBigInteger('wormholeClassID')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.map_stargates', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedInteger('destinationSolarSystemID')->nullable();
            $table->unsignedBigInteger('destinationStargateID')->nullable();
            $table->json('destination')->nullable();
            $table->jsonb('position');
            $table->unsignedInteger('solarSystemID');
            $table->unsignedInteger('typeID');
            $table->string('hash');
        });

        Schema::create('sde.map_stars', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->double('radius');
            $table->unsignedInteger('solarSystemID');
            $table->jsonb('statistics')->nullable();
            $table->unsignedInteger('typeID');
            $table->string('hash');
        });

        Schema::create('sde.npc_corporations', function (Blueprint $table) {
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

        Schema::create('sde.npc_stations', function (Blueprint $table) {
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

        Schema::create('sde.planet_schematics', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('cycleTime');
            $table->json('name');
            $table->json('pins');
            $table->json('types');
            $table->string('hash');
        });

        Schema::create('sde.skin_licenses', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->integer('duration');
            $table->unsignedInteger('licenseTypeID');
            $table->unsignedInteger('skinID');
            $table->boolean('isSingleUse')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.sovereignty_upgrades', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('fuel')->nullable();
            $table->string('mutually_exclusive_group');
            $table->unsignedBigInteger('power_allocation')->nullable();
            $table->unsignedBigInteger('workforce_allocation')->nullable();
            $table->unsignedBigInteger('workforce_production')->nullable();
            $table->unsignedBigInteger('power_production')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.station_services', function (Blueprint $table) {
            $table->unsignedTinyInteger('_key')->primary();
            $table->json('serviceName');
            $table->json('description')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.translation_languages', function (Blueprint $table) {
            $table->string('_key', 5)->primary();
            $table->string('name');
            $table->string('hash');
        });

        Schema::create('sde.type_bonuses', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('roleBonuses')->nullable();
            $table->json('types')->nullable();
            $table->unsignedBigInteger('iconID')->nullable();
            $table->json('miscBonuses')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.type_dogmas', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('dogmaAttributes');
            $table->json('dogmaEffects')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.clone_grades', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();

            $table->string('name');

            // Hash column (e.g. sha256)
            $table->string('hash')->index();

            $table->jsonb('skills');
        });

        Schema::create('sde.mercenary_tactical_operations', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();

            $table->integer('anarchy_impact');
            $table->integer('development_impact');
            $table->integer('infomorph_bonus');

            // Multilingual fields → JSONB
            $table->jsonb('description');
            $table->jsonb('name');

            // Hash for change detection
            $table->string('hash')->index();
        });

        Schema::create('sde.map_secondary_suns', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();

            $table->unsignedInteger('effectBeaconTypeID');
            $table->unsignedInteger('solarSystemID');
            $table->unsignedInteger('typeID');

            // Nested object → JSONB
            $table->jsonb('position');

            // Hash for change detection
            $table->string('hash')->index();
        });

        Schema::create('sde.compressible_types', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();

            $table->unsignedInteger('compressedTypeID');

            // Hash for change detection
            $table->string('hash')->index();
        });

        Schema::table('sde.types', function (Blueprint $table) {
            $table->integer('metaLevel')->nullable();
        });

        Schema::create('sde.sde_version', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('version')->nullable();
            $table->timestamps();
        });

        DB::table(('sde.sde_version'))->insert([
            ['id' => 'current_version', 'version' => null],
            ['id' => 'supported_version', 'version' => 3316380],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sde.agents_in_space');
        Schema::dropIfExists('sde.agent_types');
        Schema::dropIfExists('sde.dynamic_item_attributes');
        Schema::dropIfExists('sde.graphics');
        Schema::dropIfExists('sde.map_moons');
        Schema::dropIfExists('sde.map_solar_systems');
        Schema::dropIfExists('sde.npc_characters');
        Schema::dropIfExists('sde.planet_resources');
        Schema::dropIfExists('sde.races');
        Schema::dropIfExists('sde.skins');
        Schema::dropIfExists('sde.bloodlines');
        Schema::dropIfExists('sde.blueprints');
        Schema::dropIfExists('sde.categories');
        Schema::dropIfExists('sde.certificates');
        Schema::dropIfExists('sde.dogma_attribute_categories');
        Schema::dropIfExists('sde.dogma_units');
        Schema::dropIfExists('sde.freelance_job_schemas');
        Schema::dropIfExists('sde.icons');
        Schema::dropIfExists('sde.map_planets');
        Schema::dropIfExists('sde.map_regions');
        Schema::dropIfExists('sde.market_groups');
        Schema::dropIfExists('sde.masteries');
        Schema::dropIfExists('sde.types');
        Schema::dropIfExists('sde.type_materials');
        Schema::dropIfExists('sde.station_operations');
        Schema::dropIfExists('sde.skin_materials');
        Schema::dropIfExists('sde.npc_corporation_divisions');
        Schema::dropIfExists('sde.meta_groups');
        Schema::dropIfExists('sde.landmarks');
        Schema::dropIfExists('sde.groups');
        Schema::dropIfExists('sde.factions');
        Schema::dropIfExists('sde.dogma_effects');
        Schema::dropIfExists('sde.dogma_attributes');
        Schema::dropIfExists('sde.dbuff_collections');
        Schema::dropIfExists('sde.corporation_activities');
        Schema::dropIfExists('sde.control_tower_resources');
        Schema::dropIfExists('sde.contraband_types');
        Schema::dropIfExists('sde.ancestries');
        Schema::dropIfExists('sde.station_services');
        Schema::dropIfExists('sde.sovereignty_upgrades');
        Schema::dropIfExists('sde.skin_licenses');
        Schema::dropIfExists('sde.planet_schematics');
        Schema::dropIfExists('sde.npc_stations');
        Schema::dropIfExists('sde.npc_corporations');
        Schema::dropIfExists('sde.map_stars');
        Schema::dropIfExists('sde.map_stargates');
        Schema::dropIfExists('sde.map_constellations');
        Schema::dropIfExists('sde.map_asteroid_belts');
        Schema::dropIfExists('sde.type_dogmas');
        Schema::dropIfExists('sde.type_bonuses');
        Schema::dropIfExists('sde.translation_languages');
        Schema::dropIfExists('sde.clone_grades');
        Schema::dropIfExists('sde.mercenary_tactical_operations');
        Schema::dropIfExists('sde.map_secondary_suns');
        Schema::dropIfExists('sde.compressible_types');
        Schema::dropIfExists('sde.sde_version');
    }
};
