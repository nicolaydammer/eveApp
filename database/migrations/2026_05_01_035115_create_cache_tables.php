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
        Schema::create('cache.characters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('CharacterID')->unique();
            $table->unsignedBigInteger('alliance_id')->nullable();
            $table->unsignedBigInteger('corporation_id');
            $table->unsignedBigInteger('faction_id')->nullable();
            $table->unsignedBigInteger('bloodline_id');
            $table->bigInteger('race_id');
            $table->dateTime('birthday');
            $table->text('description')->nullable();
            $table->string('gender');
            $table->string('name');
            $table->double('security_status')->nullable();
            $table->text('title')->nullable();
            $table->timestamps();
        });

        Schema::create('cache.corporations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('corporation_id')->unique();
            $table->bigInteger('alliance_id')->nullable();
            $table->bigInteger('ceo_id');
            $table->bigInteger('creator_id');
            $table->dateTime('date_founded');
            $table->text('description')->nullable();
            $table->bigInteger('faction_id')->nullable();
            $table->bigInteger('home_station_id')->nullable();
            $table->bigInteger('member_count');
            $table->string('name');
            $table->bigInteger('shares')->nullable();
            $table->double('tax_rate');
            $table->string('ticker');
            $table->string('url')->nullable();
            $table->boolean('war_eligible')->nullable();
            $table->timestamps();
        });

        Schema::create('cache.alliances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('alliance_id')->unique();
            $table->bigInteger('creator_corporation_id');
            $table->bigInteger('creator_id');
            $table->dateTime('date_founded');
            $table->bigInteger('executor_corporation_id')->nullable();
            $table->bigInteger('faction_id')->nullable();
            $table->string('name');
            $table->string('ticker');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache.characters');
        Schema::dropIfExists('cache.corporations');
        Schema::dropIfExists('cache.alliances');
    }
};
