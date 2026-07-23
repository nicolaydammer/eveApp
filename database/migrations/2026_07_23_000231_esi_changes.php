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
        Schema::table('cache.corporations', function (Blueprint $blueprint) {
            $blueprint->dropColumn('tax_rate');

            $blueprint->text('friendly_fire')->nullable();
            $blueprint->jsonb('palette')->nullable();
            $blueprint->text('state')->nullable();
            $blueprint->jsonb('tax_rates')->nullable();
            $blueprint->text('type')->nullable();
            $blueprint->unsignedBigInteger('ceo_id')->nullable()->change();
            $blueprint->unsignedBigInteger('creator_id')->nullable()->change();
            $blueprint->renameColumn('faction_id', 'enlisted_faction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
