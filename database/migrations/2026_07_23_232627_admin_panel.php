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
        Schema::create('configuration', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name')->unique();
            $blueprint->jsonb('configuration');
            $blueprint->timestamps();
        });

        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->boolean('is_admin')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuration');
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn('is_admin');
        });
    }
};
