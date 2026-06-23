<?php

use App\Domain\Synchronization\Synchronizations\IndustryCostIndices;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cache.industry_cost_indices', function (Blueprint $table) {
            $table->unsignedInteger('solar_system_id')->primary();

            $table->double('manufacturing')->nullable();
            $table->double('researching_material_efficiency')->nullable();
            $table->double('researching_time_efficiency')->nullable();
            $table->double('copying')->nullable();
            $table->double('invention')->nullable();
            $table->double('reaction')->nullable();
            $table->double('reverse_engineering')->nullable();
            $table->double('duplicating')->nullable();

            $table->timestamp('synced_at');

            $table->timestamps();
        });

        DB::table('synchronizations')->insert([
            'name' => IndustryCostIndices::NAME,
            'enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cache.industry_cost_indices');

        DB::table('synchronizations')
            ->where('name', IndustryCostIndices::NAME)
            ->delete();
    }
};
