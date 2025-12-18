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

            // Store the nested arrays of attribute data as JSON.
            $table->json('attributeIDs');

            // Store the nested input/output mapping data as JSON.
            $table->json('inputOutputMapping');
            $table->string('hash');
        });

        Schema::create('graphics', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();

            $table->string('graphicFile', 255)->nullable();
            $table->string('iconFolder', 255)->nullable();
            $table->string('sofFactionName', 100)->nullable();
            $table->string('sofHullName', 100)->nullable();
            $table->string('sofRaceName', 100)->nullable();
            $table->string('hash');
        });

        Schema::create('map_moons', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedInteger('solarSystemID')->index();
            $table->unsignedBigInteger('orbitID')->index();
            $table->unsignedSmallInteger('typeID');
            $table->unsignedSmallInteger('celestialIndex');
            $table->unsignedSmallInteger('orbitIndex');
            $table->float('radius');
            $table->double('x');
            $table->double('y');
            $table->double('z');
            $table->json('attributes');
            $table->json('statistics');
            $table->json('npcStationIDs')->nullable();
            $table->string('hash');
        });

        Schema::create('map_solar_systems', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary(); // Used as Primary Key

            $table->boolean('border')->nullable();
            $table->unsignedInteger('constellationID')->index();
            $table->boolean('hub')->nullable();
            $table->boolean('international')->nullable();
            $table->double('luminosity', 10, 6)->nullable();

            // Nested data stored as JSONB
            $table->jsonb('name');
            $table->jsonb('planetIDs');
            $table->jsonb('position');
            $table->jsonb('position2D');

            $table->bigInteger('radius')->nullable();
            $table->unsignedInteger('regionID')->index();
            $table->boolean('regional')->nullable();
            $table->string('securityClass', 5);
            $table->double('securityStatus', 10, 6);
            $table->unsignedInteger('starID')->nullable()->index();
            $table->jsonb('stargateIDs');

            // Optional/less common fields
            $table->boolean('corridor')->nullable();
            $table->boolean('fringe')->nullable();
            $table->unsignedSmallInteger('wormholeClassID')->nullable();
            $table->string('visualEffect', 100)->nullable();
            $table->string('hash');
        });

        Schema::create('npc_characters', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary(); // Character ID (Primary Key)

            $table->unsignedSmallInteger('bloodlineID');
            $table->boolean('ceo');
            $table->unsignedBigInteger('corporationID')->index();
            $table->boolean('gender');
            $table->unsignedBigInteger('locationID')->index();

            // Nested localized name object
            $table->jsonb('name');

            $table->unsignedSmallInteger('raceID');
            $table->dateTime('startDate');
            $table->boolean('uniqueName');

            // Optional fields (Skills is an array of objects)
            $table->jsonb('skills')->nullable();
            $table->unsignedSmallInteger('ancestryID')->nullable();
            $table->unsignedSmallInteger('careerID')->nullable();
            $table->unsignedSmallInteger('schoolID')->nullable();
            $table->unsignedSmallInteger('specialityID')->nullable();
            $table->string('hash');
        });

        Schema::create('planet_resources', function (Blueprint $table) {
            // Planet ID (Primary Key)
            $table->unsignedBigInteger('_key')->primary();

            // Resource attributes
            $table->integer('power')->nullable();
            $table->integer('workforce')->nullable();

            // Nested data (reagent object) stored as JSONB
            $table->jsonb('reagent')->nullable();
            $table->string('hash');
        });

        Schema::create('races', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
            $table->jsonb('name');
            $table->jsonb('description')->nullable();
            $table->unsignedSmallInteger('iconID')->nullable()->index();
            $table->unsignedInteger('shipTypeID')->nullable()->index();
            $table->jsonb('skills')->nullable();
            $table->string('hash');
        });

        Schema::create('skins', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->string('internalName');
            $table->unsignedSmallInteger('skinMaterialID')->index();
            $table->jsonb('types');
            $table->boolean('allowCCPDevs');
            $table->boolean('visibleSerenity');
            $table->boolean('visibleTranquility');
            $table->boolean('isStructureSkin')->nullable();
            $table->string('hash');
        });

        Schema::create('bloodlines', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
            $table->jsonb('name');
            $table->jsonb('description');
            $table->unsignedTinyInteger('charisma');
            $table->unsignedTinyInteger('intelligence');
            $table->unsignedTinyInteger('memory');
            $table->unsignedTinyInteger('perception');
            $table->unsignedTinyInteger('willpower');
            $table->unsignedSmallInteger('iconID')->nullable()->index();
            $table->unsignedBigInteger('corporationID')->index();
            $table->unsignedTinyInteger('raceID')->index();
            $table->string('hash');
        });

        Schema::create('blueprints', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
            $table->jsonb('activities');
            $table->unsignedSmallInteger('blueprintTypeID')->index();
            $table->unsignedSmallInteger('maxProductionLimit');
            $table->string('hash');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
            $table->jsonb('name');
            $table->unsignedSmallInteger('iconID')->nullable();
            $table->boolean('published')->default(false);
            $table->string('hash');
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->jsonb('description');
            $table->unsignedSmallInteger('groupID')->index();
            $table->jsonb('name');
            $table->jsonb('recommendedFor');
            $table->jsonb('skillTypes');
            $table->string('hash');
        });

        Schema::create('character_attributes', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->jsonb('description');
            $table->unsignedSmallInteger('iconID')->nullable();
            $table->jsonb('name');
            $table->text('notes')->nullable();
            $table->text('shortDescription');
            $table->string('hash');
        });

        Schema::create('dogma_attribute_categories', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->string('hash');
        });

        Schema::create('dogma_units', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
            $table->jsonb('description')->nullable();
            $table->jsonb('displayName')->nullable();
            $table->string('name', 50);
            $table->string('hash');
        });

        Schema::create('freelance_job_schemas', function (Blueprint $table) {
            $table->string('_key')->primary();
            $table->string('icon_id');
            $table->json('content_tags');
            $table->json('title');
            $table->json('description');
            $table->json('progress_description');
            $table->json('reward_description');
            $table->json('target_description');
            $table->json('max_contributions');
            $table->json('parameters');
            $table->string('hash');
        });

        Schema::create('icons', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->string('iconFile');
            $table->string('hash');
        });

        Schema::create('map_planets', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->integer('solar_system_id')->index();
            $table->integer('orbit_id')->index();
            $table->integer('celestial_index');
            $table->integer('type_id')->index();
            $table->bigInteger('radius');
            $table->json('position');
            $table->json('attributes');
            $table->json('statistics');
            $table->json('moon_ids')->nullable();
            $table->json('asteroid_belt_ids')->nullable();
            $table->json('npc_station_ids')->nullable();
            $table->string('hash');
        });

        Schema::create('map_regions', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('constellationIDs');
            $table->json('description');
            $table->integer('factionID')->index();
            $table->json('name');
            $table->integer('nebulaID')->nullable();
            $table->json('position');
            $table->integer('wormholeClassID')->nullable();
            $table->string('hash');
        });

        Schema::create('market_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('description');
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
            $table->unsignedSmallInteger('groupID')->nullable();
            $table->json('name');
            $table->json('description')->nullable();
            $table->double('mass')->nullable();
            $table->unsignedSmallInteger('portionSize')->nullable();
            $table->boolean('published')->nullable();
            $table->double('volume')->nullable();
            $table->double('radius')->nullable();
            $table->unsignedInteger('graphicID')->nullable();
            $table->unsignedInteger('iconID')->nullable();
            $table->unsignedInteger('soundID')->nullable();
            $table->unsignedSmallInteger('raceID')->nullable();
            $table->double('basePrice')->nullable();
            $table->double('capacity')->nullable();
            $table->unsignedSmallInteger('marketGroupID')->nullable();
            $table->string('hash');
        });

        Schema::create('meta_groups', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
            $table->json('name');
            $table->json('description')->nullable();
            $table->json('color')->nullable();
            $table->unsignedInteger('iconID')->nullable();
            $table->string('iconSuffix', 50)->nullable();
            $table->string('hash');
        });

        Schema::create('npc_corporation_divisions', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
            $table->string('internalName', 100);
            $table->json('displayName');
            $table->json('name');
            $table->json('leaderTypeName');
            $table->json('description')->nullable();
            $table->string('hash');
        });

        Schema::create('skin_materials', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('displayName');
            $table->unsignedInteger('materialSetID');
            $table->string('hash');
        });

        Schema::create('station_operations', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
            $table->unsignedSmallInteger('activityID');
            $table->json('operationName');
            $table->json('description');
            $table->float('border');
            $table->float('corridor');
            $table->float('fringe');
            $table->float('hub');
            $table->float('ratio');
            $table->float('manufacturingFactor');
            $table->float('researchFactor');
            $table->json('services');
            $table->json('stationTypes');
            $table->string('hash');
        });

        Schema::create('type_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->json('materials');
            $table->string('hash');
        });

        Schema::create('ancestries', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
            $table->unsignedSmallInteger('bloodlineID');
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
            $table->json('factions'); // Array of objects containing faction-specific contraband rules
            $table->string('hash');
        });

        Schema::create('control_tower_resources', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary(); // Control Tower Type ID
            $table->json('resources'); // Array of resource requirement objects
            $table->string('hash');
        });

        Schema::create('corporation_activities', function (Blueprint $table) {
            $table->unsignedSmallInteger('_key')->primary();
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
            $table->unsignedSmallInteger('attributeCategoryID')->nullable();
            $table->unsignedTinyInteger('dataType');
            $table->float('defaultValue');
            $table->string('description', 255)->nullable();
            $table->json('displayName')->nullable();
            $table->boolean('displayWhenZero');
            $table->boolean('highIsGood');
            $table->unsignedInteger('iconID')->nullable();
            $table->string('name', 100);
            $table->boolean('published');
            $table->boolean('stackable');
            $table->json('tooltipDescription')->nullable();
            $table->json('tooltipTitle')->nullable();
            $table->unsignedSmallInteger('unitID')->nullable();
            $table->string('hash');
        });

        Schema::create('dogma_effects', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('description')->nullable();
            $table->unsignedInteger('dischargeAttributeID')->nullable();
            $table->boolean('disallowAutoRepeat');
            $table->unsignedTinyInteger('distribution')->nullable();
            $table->unsignedInteger('durationAttributeID')->nullable();
            $table->unsignedTinyInteger('effectCategoryID');
            $table->boolean('electronicChance');
            $table->unsignedInteger('falloffAttributeID')->nullable();
            $table->string('guid', 100)->nullable();
            $table->boolean('isAssistance');
            $table->boolean('isOffensive');
            $table->boolean('isWarpSafe');
            $table->json('modifierInfo')->nullable();
            $table->string('name', 100);
            $table->boolean('propulsionChance');
            $table->boolean('published');
            $table->unsignedInteger('rangeAttributeID')->nullable();
            $table->boolean('rangeChance');
            $table->unsignedInteger('trackingSpeedAttributeID')->nullable();
            $table->string('hash');
        });

        Schema::create('factions', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->unsignedInteger('corporationID');
            $table->json('description');
            $table->boolean('isUnique')->nullable();
            $table->json('name');
            $table->unsignedInteger('solarSystemID')->nullable();
            $table->unsignedSmallInteger('stationCount')->nullable();
            $table->unsignedSmallInteger('stationSystemCount')->nullable();
            $table->string('hash');
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->boolean('anchorable');
            $table->boolean('anchored');
            $table->unsignedInteger('categoryID');
            $table->boolean('fittableNonSingleton');
            $table->json('name');
            $table->boolean('published');
            $table->boolean('useBasePrice');
            $table->string('hash');
        });

        Schema::create('landmarks', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();
            $table->json('description');
            $table->unsignedInteger('locationID');
            $table->json('name');
            $table->string('hash');
        });

        Schema::create('map_asteroid_belts', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedSmallInteger('celestialIndex');
            $table->unsignedBigInteger('orbitID');
            $table->unsignedSmallInteger('orbitIndex');
            $table->double('position_x');
            $table->double('position_y');
            $table->double('position_z');
            $table->double('radius');
            $table->unsignedInteger('solarSystemID');
            $table->double('density')->nullable();
            $table->double('eccentricity')->nullable();
            $table->double('escapeVelocity')->nullable();
            $table->boolean('locked')->nullable();
            $table->double('massDust')->nullable();
            $table->double('massGas')->nullable();
            $table->double('orbitPeriod')->nullable();
            $table->double('orbitRadius')->nullable();
            $table->double('rotationRate')->nullable();
            $table->string('spectralClass')->nullable();
            $table->double('surfaceGravity')->nullable();
            $table->double('temperature')->nullable();
            $table->unsignedSmallInteger('typeID');
            $table->string('hash');
        });

        Schema::create('map_constellations', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary();
            $table->unsignedInteger('factionID')->nullable();
            $table->json('name');
            $table->double('position_x');
            $table->double('position_y');
            $table->double('position_z');
            $table->unsignedSmallInteger('regionID');
            $table->json('solarSystemIDs');
            $table->unsignedSmallInteger('wormholeClassID')->nullable();
            $table->string('hash');
        });

        Schema::create('map_stargates', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedInteger('destination_solarSystemID');
            $table->unsignedBigInteger('destination_stargateID');
            $table->double('position_x');
            $table->double('position_y');
            $table->double('position_z');
            $table->unsignedInteger('solarSystemID');
            $table->unsignedInteger('typeID');
            $table->string('hash');
        });

        Schema::create('map_stars', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->double('radius');
            $table->unsignedInteger('solarSystemID');
            $table->double('age')->nullable();
            $table->double('life')->nullable();
            $table->double('luminosity')->nullable();
            $table->string('spectralClass')->nullable();
            $table->double('temperature')->nullable();
            $table->unsignedInteger('typeID');
            $table->string('hash');
        });

        Schema::create('npc_corporations', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('ceoID');
            $table->boolean('deleted');
            $table->json('description');
            $table->string('extent');
            $table->boolean('hasPlayerPersonnelManager');
            $table->unsignedInteger('initialPrice');
            $table->integer('memberLimit');
            $table->float('minSecurity');
            $table->integer('minimumJoinStanding');
            $table->json('name');
            $table->boolean('sendCharTerminationMessage');
            $table->unsignedInteger('shares');
            $table->string('size');
            $table->unsignedBigInteger('stationID')->nullable();
            $table->float('taxRate');
            $table->string('tickerName');
            $table->boolean('uniqueName');
            $table->json('allowedMemberRaces')->nullable();
            $table->json('corporationTrades')->nullable();
            $table->string('hash');
        });

        Schema::create('npc_stations', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedSmallInteger('celestialIndex');
            $table->unsignedSmallInteger('operationID');
            $table->unsignedBigInteger('orbitID');
            $table->unsignedSmallInteger('orbitIndex')->nullable();
            $table->unsignedInteger('ownerID');
            $table->double('position_x');
            $table->double('position_y');
            $table->double('position_z');
            $table->float('reprocessingEfficiency');
            $table->unsignedSmallInteger('reprocessingHangarFlag');
            $table->float('reprocessingStationsTake');
            $table->unsignedInteger('solarSystemID');
            $table->unsignedInteger('typeID');
            $table->boolean('useOperationName');
            $table->string('hash');
        });

        Schema::create('planet_schematics', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary();
            $table->unsignedSmallInteger('cycleTime');
            $table->json('name');
            $table->json('pins');
            $table->json('types');
            $table->string('hash');
        });

        Schema::create('skin_licenses', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->integer('duration');
            $table->unsignedInteger('licenseTypeID');
            $table->unsignedInteger('skinID');
            $table->boolean('isSingleUse')->nullable();
            $table->string('hash');
        });

        Schema::create('sovereignty_upgrades', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->json('fuel')->nullable();
            $table->string('mutually_exclusive_group');
            $table->unsignedSmallInteger('power_allocation');
            $table->unsignedSmallInteger('workforce_allocation');
            $table->unsignedSmallInteger('power_production')->nullable();
            $table->string('hash');
        });

        Schema::create('station_services', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->json('serviceName');
            $table->json('description')->nullable();
            $table->string('hash');
        });

        Schema::create('translation_languages', function (Blueprint $table) {
            // ID is the language code (e.g., 'en', 'ru')
            $table->string('id', 5)->primary();
            $table->string('name');
            $table->string('hash');
        });

        Schema::create('type_bonuses', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->json('roleBonuses');
            $table->json('types');
            $table->json('miscBonuses')->nullable();
            $table->string('hash');
        });

        Schema::create('type_dogmas', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
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
