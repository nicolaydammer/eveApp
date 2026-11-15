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
        Schema::table('sde.blueprints', function (Blueprint $blueprint) {
            $blueprint->dropColumn('blueprintTypeID');
        });

        Schema::table('sde.mercenary_tactical_operations', function (Blueprint $blueprint) {
            $blueprint->dropColumn('dungeonID');
        });

        Schema::table('sde.skin_licenses', function (Blueprint $blueprint) {
            $blueprint->dropColumn('licenseTypeID');
        });

        (new VersionRepository())->setSupportedVersion(3698585);

        Schema::table('sde.system_dbuff_emitters', function (Blueprint $blueprint) {
            $blueprint->jsonb('dbuffs')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sde.blueprints', function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('blueprintTypeID')->nullable();
        });

        Schema::table('sde.mercenary_tactical_operations', function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('dungeonID')->nullable();
        });

        Schema::table('sde.skin_licenses', function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('licenseTypeID')->nullable();
        });
    }
};
