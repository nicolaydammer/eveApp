<?php

use App\Domain\SDE\Services\State\VersionRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sde.types', function (Blueprint $table) {
            $table->unsignedBigInteger('techLevel')
                ->nullable();

            $table->unsignedBigInteger('shipTreeGroupID')
                ->nullable();
        });

        Schema::table('sde.skins', function (Blueprint $table) {
            $table->dropColumn(['skinDescription']);
        });

        (new VersionRepository())->setSupportedVersion(3409592);
    }

    public function down(): void
    {
        Schema::table('sde.types', function (Blueprint $table) {
            $table->dropColumn([
                'techLevel',
                'shipTreeGroupID',
            ]);
        });

        Schema::table('sde.skins', function (Blueprint $table) {
            $table->jsonb('skinDescription');
        });
    }
};
