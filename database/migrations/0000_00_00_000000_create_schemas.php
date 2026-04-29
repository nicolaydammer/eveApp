<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP SCHEMA IF EXISTS "sde" CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS "market" CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS "cache" CASCADE');
        DB::statement('CREATE SCHEMA IF NOT EXISTS "sde"');
        DB::statement('CREATE SCHEMA IF NOT EXISTS "market"');
        DB::statement('CREATE SCHEMA IF NOT EXISTS "cache"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP SCHEMA IF EXISTS "sde" CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS "market" CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS "cache" CASCADE');
    }
};
