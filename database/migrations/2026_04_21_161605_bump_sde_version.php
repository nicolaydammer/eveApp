<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::update('UPDATE sde_version SET version = 3311451 WHERE id = 2');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
