<?php

use App\Domain\SDE\Services\State\VersionRepository;
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
        Schema::table('sde.types', function (Blueprint $blueprint) {
            $blueprint->double('packagedVolume')->nullable();
            $blueprint->boolean('isDynamicType')->default(false)->nullable();
            $blueprint->boolean('isRepackable')->default(false)->nullable();
        });

        Schema::create('sde.corporation_role_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('appliesTo');
            $table->string('appliesToGrantable');
            $table->boolean('isDivisional');
            $table->boolean('isLocational');
            $table->jsonb('name');
            $table->string('hash');
        });

        Schema::create('sde.corporation_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('description');
            $table->jsonb('name');
            $table->jsonb('roleGroupIDs')->nullable();
            $table->string('shortName');
            $table->string('hash');
        });

        Schema::create('sde.expert_systems', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('associatedShipTypes')->nullable();
            $table->unsignedInteger('durationDays');
            $table->boolean('hidden');
            $table->string('internalName');
            $table->boolean('retired');
            $table->jsonb('skillsGranted');
            $table->string('hash');
        });

        Schema::create('sde.fighter_abilities', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->boolean('disallowInHighSec');
            $table->boolean('disallowInLowSec');
            $table->jsonb('displayName');
            $table->unsignedBigInteger('iconID');
            $table->string('targetMode');
            $table->jsonb('tooltipText')->nullable();
            $table->unsignedBigInteger('turretGraphicID')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.fighter_abilities_by_type', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('abilitySlot0');
            $table->jsonb('abilitySlot1');
            $table->jsonb('abilitySlot2')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.industry_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('description');
            $table->string('name');
            $table->string('hash');
        });

        Schema::create('sde.industry_assembly_lines', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('activityID');
            $table->double('baseCostMultiplier')->nullable();
            $table->double('baseMaterialMultiplier');
            $table->double('baseTimeMultiplier');
            $table->text('description')->nullable();
            $table->jsonb('detailsPerCategory')->nullable();
            $table->jsonb('detailsPerGroup')->nullable();
            $table->jsonb('detailsPerTypeList')->nullable();
            $table->string('name');
            $table->string('hash');
        });

        Schema::create('sde.industry_installation_types', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('assemblyLines');
            $table->string('hash');
        });

        Schema::create('sde.industry_modifier_sources', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('copying')->nullable();
            $table->jsonb('invention')->nullable();
            $table->jsonb('manufacturing')->nullable();
            $table->jsonb('reaction')->nullable();
            $table->jsonb('researchMaterial')->nullable();
            $table->jsonb('researchTime')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.industry_target_filters', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('categoryIDs')->nullable();
            $table->jsonb('groupIDs')->nullable();
            $table->string('name');
            $table->string('hash');
        });

        Schema::create('sde.link_with_ship', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->boolean('applyPvpFlag');
            $table->boolean('canRelink');
            $table->double('characterEnergyCost')->nullable();
            $table->unsignedInteger('dbuffPostLinkDuration');
            $table->jsonb('dbuffs');
            $table->boolean('generateCynoInhibitor');
            $table->boolean('keepDbuffDurationOnLinkBreak');
            $table->unsignedInteger('linkDuration');
            $table->unsignedBigInteger('linkEffectGraphicIDOverride');
            $table->unsignedBigInteger('linkableShipTypeListID');
            $table->unsignedInteger('maxLinkRange');
            $table->boolean('omegaOnly');
            $table->double('solarsystemInterferenceCost')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.metenox_moon_drill', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedInteger('miningCycleTime');
            $table->double('miningEfficiency');
            $table->unsignedInteger('reagentsConsumedPerCycle');
            $table->string('hash');
        });

        Schema::create('sde.notification_types', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('internalName');
            $table->jsonb('description')->nullable();
            $table->jsonb('journalMessage')->nullable();
            $table->jsonb('displayName')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.proximity_trap', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedInteger('dbuffDuration');
            $table->jsonb('dbuffs')->nullable();
            $table->unsignedInteger('forceDecloakDuration')->nullable();
            $table->unsignedInteger('resetDelay')->nullable();
            $table->boolean('showPerimeterLights');
            $table->unsignedInteger('triggerDelay');
            $table->unsignedBigInteger('triggerFilterTypeListID');
            $table->unsignedInteger('triggerRange');
            $table->string('hash');
        });

        Schema::create('sde.school_map', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('schoolID');
            $table->unsignedBigInteger('solarSystemID');
            $table->string('hash');
        });

        Schema::create('sde.schools', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('careerAgents')->nullable();
            $table->unsignedBigInteger('careerID');
            $table->jsonb('characterDescription')->nullable();
            $table->unsignedBigInteger('corporationID');
            $table->jsonb('description')->nullable();
            $table->unsignedBigInteger('iconID')->nullable();
            $table->boolean('isStarterSpaceSchool')->nullable();
            $table->jsonb('name');
            $table->unsignedBigInteger('raceID');
            $table->jsonb('startingStations')->nullable();
            $table->jsonb('title')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.skill_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->unsignedBigInteger('careerPathID')->nullable();
            $table->jsonb('description');
            $table->unsignedBigInteger('factionID')->nullable();
            $table->string('internalName');
            $table->jsonb('milestones');
            $table->jsonb('name');
            $table->unsignedBigInteger('npcCorporationDivision')->nullable();
            $table->jsonb('skillRequirements');
            $table->string('hash');
        });

        Schema::create('sde.skinr_slots_to_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('_value');
            $table->string('hash');
        });

        Schema::create('sde.station_standings_restrictions', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('services');
            $table->string('hash');
        });

        Schema::create('sde.system_dbuff_emitters', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('dbuffs');
            $table->integer('duration');
            $table->boolean('excludeProtected')->nullable();
            $table->integer('interval');
            $table->string('hash');
        });

        Schema::create('sde.system_wide_effects', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->jsonb('dbuffs')->nullable();
            $table->unsignedBigInteger('eligibleTypeListID')->nullable();
            $table->unsignedBigInteger('environmentTypeID')->nullable();
            $table->string('hash');
        });

        Schema::create('sde.type_elements', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('elements')->nullable();
        });

        Schema::create('sde.graphic_material_sets', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('colorHull')->nullable();
            $table->jsonb('colorPrimary')->nullable();
            $table->jsonb('colorSecondary')->nullable();
            $table->jsonb('colorWindow')->nullable();
            $table->string('custommaterial1')->nullable();
            $table->string('custommaterial2')->nullable();
            $table->text('description');
            $table->string('material1')->nullable();
            $table->string('material2')->nullable();
            $table->string('material3')->nullable();
            $table->string('material4')->nullable();
            $table->string('resPathInsert')->nullable();
            $table->string('sofFactionName')->nullable();
            $table->string('sofPatternName')->nullable();
            $table->string('sofRaceHint')->nullable();
        });

        Schema::create('sde.missions', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->unsignedBigInteger('agentTypeID')->nullable();
            $table->unsignedBigInteger('corporationID')->nullable();
            $table->jsonb('courierMission')->nullable();
            $table->bigInteger('expirationTime')->nullable();
            $table->jsonb('extraStandings')->nullable();
            $table->unsignedBigInteger('factionID')->nullable();
            $table->boolean('hasStandingRewards');
            $table->bigInteger('initialAgentGiftQuantity')->nullable();
            $table->unsignedBigInteger('initialAgentGiftTypeID')->nullable();
            $table->jsonb('killMission')->nullable();
            $table->jsonb('messages')->nullable();
            $table->jsonb('missionRewards')->nullable();
            $table->jsonb('name');
        });

        Schema::create('sde.ship_tree_factions', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('description');
            $table->jsonb('elements');
            $table->string('icon');
        });

        Schema::create('sde.skinr_component_point_values', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('_value');
        });

        Schema::create('sde.skinr_components', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('associatedTypeIds');
            $table->unsignedInteger('category');
            $table->string('finish');
            $table->string('iconFile');
            $table->jsonb('name');
            $table->string('projectionTypeU');
            $table->string('projectionTypeV');
            $table->boolean('published');
            $table->unsignedInteger('rarity');
            $table->string('resourceFile');
            $table->jsonb('sequenceBinder');
        });

        Schema::create('sde.skinr_slot_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->string('name');
        });

        Schema::create('sde.skinr_slot_names', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->string('name');
        });

        Schema::create('sde.skinr_slots', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('allowedDesignComponentCategories');
            $table->unsignedInteger('category');
            $table->jsonb('name');
        });

        Schema::create('sde.skinr_tier_thresholds', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('_value');
        });

        Schema::create('sde.accounting_entry_types', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('description')->nullable();
            $table->string('internalName');
            $table->jsonb('journalMessage')->nullable();
            $table->jsonb('name');
        });

        Schema::create('sde.applied_proximity_effects', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('dbuffs');
            $table->integer('delaySeconds');
            $table->integer('radius');
        });

        Schema::create('sde.epic_arcs', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->integer('arcRestartInterval');
            $table->unsignedBigInteger('factionID')->nullable();
            $table->unsignedBigInteger('iconID');
            $table->jsonb('missions');
            $table->jsonb('name');
        });

        Schema::create('sde.military_campaign_objectives', function (Blueprint $table) {
            $table->uuid('_key')->primary();
            $table->string('hash');
            $table->jsonb('annotations')->nullable();
            $table->uuid('campaignID');
            $table->string('careerPath');
            $table->jsonb('contentTags');
            $table->jsonb('contributionMethodConfiguration');
            $table->jsonb('issuer');
            $table->bigInteger('maxProgressPerParticipant');
            $table->unsignedBigInteger('presentingCharacterID')->nullable();
            $table->jsonb('rewards');
            $table->jsonb('subtitle');
            $table->bigInteger('targetProgress');
            $table->jsonb('title');
        });

        Schema::create('sde.military_campaigns', function (Blueprint $table) {
            $table->uuid('_key')->primary();
            $table->string('hash');
            $table->jsonb('annotations')->nullable();
            $table->jsonb('issuer');
            $table->jsonb('subtitle');
            $table->integer('targetProgress');
            $table->jsonb('title');
        });

        Schema::create('sde.ship_tree_elements', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('description');
            $table->string('icon');
            $table->jsonb('name');
        });

        Schema::create('sde.ship_tree_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('description')->nullable();
            $table->jsonb('elements')->nullable();
            $table->string('icon');
            $table->string('iconLarge');
            $table->string('iconSmall');
            $table->string('iconSmallNPC');
            $table->jsonb('name');
            $table->jsonb('preReqSkills')->nullable();
        });

        Schema::create('sde.skinr_component_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->string('name');
        });

        Schema::create('sde.skinr_component_rarities', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('name');
            $table->integer('rank');
        });

        Schema::create('sde.skinr_slot_configurations', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->boolean('allowAllShips')->nullable();
            $table->jsonb('config')->nullable();
            $table->string('name');
            $table->integer('priority');
            $table->jsonb('ships')->nullable();
        });

        (new VersionRepository())->setSupportedVersion(3492266);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sde.types', function (Blueprint $blueprint) {
            $blueprint->dropColumn('packagedVolume');
            $blueprint->dropColumn('isDynamicType');
            $blueprint->dropColumn('isRepackable');
        });

        Schema::dropIfExists('sde.type_elements');
        Schema::dropIfExists('sde.notification_types');
        Schema::dropIfExists('sde.system_dbuff_emitters');
        Schema::dropIfExists('sde.industry_target_filters');
        Schema::dropIfExists('sde.industry_modifier_sources');
        Schema::dropIfExists('sde.industry_installation_types');
        Schema::dropIfExists('sde.industry_assembly_lines');
        Schema::dropIfExists('sde.industry_activities');
        Schema::dropIfExists('sde.fighter_abilities_by_type');
        Schema::dropIfExists('sde.fighter_abilities');
        Schema::dropIfExists('sde.expert_systems');
        Schema::dropIfExists('sde.corporation_roles');
        Schema::dropIfExists('sde.corporation_role_groups');
        Schema::dropIfExists('sde.system_wide_effects');
        Schema::dropIfExists('sde.station_standings_restrictions');
        Schema::dropIfExists('sde.skinr_slots_to_materials');
        Schema::dropIfExists('sde.skill_plans');
        Schema::dropIfExists('sde.schools');
        Schema::dropIfExists('sde.school_map');
        Schema::dropIfExists('sde.proximity_trap');
        Schema::dropIfExists('sde.metenox_moon_drill');
        Schema::dropIfExists('sde.link_with_ship');
        Schema::dropIfExists('sde.skinr_tier_thresholds');
        Schema::dropIfExists('sde.skinr_slots');
        Schema::dropIfExists('sde.skinr_slot_names');
        Schema::dropIfExists('sde.skinr_slot_categories');
        Schema::dropIfExists('sde.skinr_components');
        Schema::dropIfExists('sde.skinr_component_point_values');
        Schema::dropIfExists('sde.ship_tree_factions');
        Schema::dropIfExists('sde.missions');
        Schema::dropIfExists('sde.graphic_material_sets');
        Schema::dropIfExists('sde.skinr_slot_configurations');
        Schema::dropIfExists('sde.skinr_component_rarities');
        Schema::dropIfExists('sde.skinr_component_categories');
        Schema::dropIfExists('sde.ship_tree_groups');
        Schema::dropIfExists('sde.ship_tree_elements');
        Schema::dropIfExists('sde.military_campaigns');
        Schema::dropIfExists('sde.military_campaign_objectives');
        Schema::dropIfExists('sde.epic_arcs');
        Schema::dropIfExists('sde.applied_proximity_effects');
        Schema::dropIfExists('sde.accounting_entry_types');
    }
};
