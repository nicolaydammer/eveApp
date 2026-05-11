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
        Schema::create('sde.archetypes', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->jsonb('description')->nullable();
            $table->jsonb('title')->nullable();
        });

        Schema::create('sde.dungeons', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->unsignedBigInteger('archetypeID')->nullable();
            $table->unsignedBigInteger('factionID')->nullable();
            $table->jsonb('allowedShipsList')->nullable();
            $table->jsonb('description')->nullable();
            $table->jsonb('gameplayDescription')->nullable();
            $table->jsonb('name')->nullable();
        });

        Schema::create('sde.type_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();
            $table->string('hash');
            $table->text('name')->nullable();
            $table->jsonb('displayName')->nullable();
            $table->jsonb('displayDescription')->nullable();
            $table->jsonb('includedTypeIDs')->nullable();
            $table->jsonb('includedGroupIDs')->nullable();
            $table->jsonb('includedCategoryIDs')->nullable();
            $table->jsonb('excludedTypeIDs')->nullable();
            $table->jsonb('excludedGroupIDs')->nullable();
            $table->jsonb('excludedCategoryIDs')->nullable();
        });

        Schema::table('sde.mercenary_tactical_operations', function (Blueprint $table) {
            $table->renameColumn('anarchy_impact', 'anarchyImpact');
            $table->renameColumn('development_impact', 'developmentImpact');
            $table->renameColumn('infomorph_bonus', 'infomorphBonus');
            $table->bigInteger('dungeonID');
        });

        (new VersionRepository())->setSupportedVersion(3333874);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sde.archetypes');
        Schema::dropIfExists('sde.dungeons');
        Schema::dropIfExists('sde.type_lists');
    }
};
