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
        Schema::create('clone_grades', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();

            $table->string('name');

            // Hash column (e.g. sha256)
            $table->string('hash')->index();

            $table->jsonb('skills');
        });

        Schema::create('mercenary_tactical_operations', function (Blueprint $table) {
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

        Schema::create('map_secondary_suns', function (Blueprint $table) {
            $table->unsignedBigInteger('_key')->primary();

            $table->unsignedInteger('effectBeaconTypeID');
            $table->unsignedInteger('solarSystemID');
            $table->unsignedInteger('typeID');

            // Nested object → JSONB
            $table->jsonb('position');

            // Hash for change detection
            $table->string('hash')->index();
        });

        Schema::create('compressible_types', function (Blueprint $table) {
            $table->unsignedInteger('_key')->primary();

            $table->unsignedInteger('compressedTypeID');

            // Hash for change detection
            $table->string('hash')->index();
        });

        Schema::table('types', function (Blueprint $table) {
            $table->integer('metaLevel')->nullable();
        });

        DB::update('UPDATE sde_version SET version = 3304841 WHERE id = 2');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clone_grades');
        Schema::dropIfExists('mercenary_tactical_operations');
        Schema::dropIfExists('map_secondary_suns');
        Schema::dropIfExists('compressible_types');
    }
};
