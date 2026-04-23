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
        // todo: change id's from ints to string and change the id's to proper names instead of 1 and 2 for supported and current version.
        Schema::table('sde_version', function (Blueprint $table) {
            $table->string('id')->change();
        });

        DB::table('sde_version')->where('id', 1)->update(['id' => 'current_version']);
        DB::table('sde_version')->where('id', 2)->update(['id' => 'supported_version']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
