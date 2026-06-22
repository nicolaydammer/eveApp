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
        Schema::create('sde.character_titles', function (Blueprint $table) {
            $table->string('_key')->primary();
            $table->jsonb('name');
            $table->string('hash');
        });

        (new VersionRepository())->setSupportedVersion(3396210);

        Schema::table('cache.characters', function (Blueprint $table) {
            $table->renameColumn('title', 'corporation_title');
            $table->string('character_title_id')->nullable();
            $table->unsignedBigInteger('achievement_score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('sde.character_titles');
        Schema::table('cache.characters', function (Blueprint $table) {
            $table->dropColumn(['corporation_title', 'character_title_id', 'achievement_score']);
            $table->text('title')->nullable();
        });
    }
};
