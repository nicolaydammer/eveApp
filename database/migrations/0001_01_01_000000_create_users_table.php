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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('main_character_id')->unique();
            $table->timestamps();
        });

        Schema::create('characters', function (Blueprint $table) {
            $table->integer('CharacterID')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('CharacterName');
            $table->text('accessToken');
            $table->string('refreshToken');
            $table->dateTime('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('characters');

    }
};
