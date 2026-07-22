<?php

use App\Domain\Synchronization\Synchronizations\ReferenceMarketPrices;
use App\Domain\Synchronization\Synchronizations\RegionMarketOrders;
use App\Domain\Synchronization\Synchronizations\StructureMarketOrders;
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
        Schema::create('market.market_reference_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->primary();

            $table->decimal('adjusted_price', 20, 2)->nullable();
            $table->decimal('average_price', 20, 2)->nullable();
        });

        Schema::create('market.structure_market_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->primary();

            $table->foreignId('last_sync_run_id');

            $table->foreign('last_sync_run_id')
                ->references('id')
                ->on('public.synchronization_runs')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('structure_id');
            $table->unsignedBigInteger('location_id');

            $table->unsignedInteger('type_id');
            $table->unsignedInteger('system_id')->nullable();

            $table->boolean('is_buy_order');

            $table->decimal('price', 20, 2);

            $table->unsignedBigInteger('volume_total');
            $table->unsignedBigInteger('volume_remain');
            $table->unsignedInteger('min_volume');

            $table->unsignedInteger('duration');

            $table->timestamp('issued');

            $table->timestamps();

            $table->index('structure_id');
            $table->index('type_id');
            $table->index(['structure_id', 'type_id']);
            $table->index(['structure_id', 'is_buy_order']);
            $table->index(['structure_id', 'last_sync_run_id']);
        });

        Schema::create('market.structure_market_order_history', function (Blueprint $table) {
            $table->id();

            $table->foreignId('synchronization_run_id');

            $table->foreign('synchronization_run_id')
                ->references('id')
                ->on('public.synchronization_runs')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('order_id');

            $table->unsignedBigInteger('structure_id');
            $table->unsignedBigInteger('location_id');

            $table->unsignedInteger('type_id');
            $table->unsignedInteger('system_id')->nullable();

            $table->boolean('is_buy_order');

            $table->decimal('price', 20, 2);

            $table->unsignedBigInteger('volume_total');
            $table->unsignedBigInteger('volume_remain');
            $table->unsignedInteger('min_volume');

            $table->unsignedInteger('duration');

            $table->timestamp('issued');

            $table->timestamps();

            /*
             * Primary lookup indexes
             */
            $table->index('synchronization_run_id');
            $table->index('order_id');
            $table->index('structure_id');
            $table->index('type_id');

            /*
             * Statistics queries
             */
            $table->index([
                'structure_id',
                'type_id',
                'created_at',
            ]);

            /*
             * Order lifetime queries
             */
            $table->index([
                'order_id',
                'created_at',
            ]);

            /*
             * Snapshot reconstruction
             */
            $table->index([
                'synchronization_run_id',
                'structure_id',
            ]);
        });

        Schema::create('market.region_market_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->primary();

            $table->foreignId('last_sync_run_id');

            $table->foreign('last_sync_run_id')
                ->references('id')
                ->on('public.synchronization_runs')
                ->cascadeOnDelete();

            $table->unsignedInteger('region_id');

            $table->unsignedBigInteger('location_id');
            $table->unsignedInteger('system_id');

            $table->unsignedInteger('type_id');

            $table->boolean('is_buy_order');

            $table->decimal('price', 20, 2);

            $table->string('range', 20);

            $table->unsignedBigInteger('volume_total');
            $table->unsignedBigInteger('volume_remain');
            $table->unsignedInteger('min_volume');

            $table->unsignedInteger('duration');

            $table->timestamp('issued');

            $table->timestamps();

            $table->index('region_id');
            $table->index('type_id');
            $table->index(['region_id', 'type_id']);
            $table->index(['region_id', 'is_buy_order']);
            $table->index(['region_id', 'last_sync_run_id']);
        });

        Schema::create('market.region_market_order_history', function (Blueprint $table) {
            $table->id();

            $table->foreignId('synchronization_run_id');

            $table->foreign('synchronization_run_id')
                ->references('id')
                ->on('public.synchronization_runs')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('order_id');

            $table->unsignedInteger('region_id');

            $table->unsignedBigInteger('location_id');
            $table->unsignedInteger('system_id');

            $table->unsignedInteger('type_id');

            $table->boolean('is_buy_order');

            $table->decimal('price', 20, 2);

            $table->string('range', 20);

            $table->unsignedBigInteger('volume_total');
            $table->unsignedBigInteger('volume_remain');
            $table->unsignedInteger('min_volume');

            $table->unsignedInteger('duration');

            $table->timestamp('issued');

            $table->timestamps();

            /*
             * Primary lookup indexes
             */
            $table->index('synchronization_run_id');
            $table->index('order_id');
            $table->index('region_id');
            $table->index('type_id');

            /*
             * Statistics queries
             */
            $table->index([
                'region_id',
                'type_id',
                'created_at',
            ]);

            /*
             * Order lifetime queries
             */
            $table->index([
                'order_id',
                'created_at',
            ]);

            /*
             * Snapshot reconstruction
             */
            $table->index([
                'synchronization_run_id',
                'region_id',
            ]);
        });

        DB::table('synchronizations')->insert([
            'name' => ReferenceMarketPrices::name(),
            'enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('synchronizations')->insert([
            'name' => RegionMarketOrders::name(),
            'enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('synchronizations')->insert([
            'name' => StructureMarketOrders::name(),
            'enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market.structure_market_order_history');
        Schema::dropIfExists('market.structure_market_orders');
        Schema::dropIfExists('market.market_reference_prices');
        Schema::dropIfExists('market.region_market_order_history');
        Schema::dropIfExists('market.region_market_orders');

        DB::table('synchronizations')
            ->where('name', ReferenceMarketPrices::name())
            ->delete();

        DB::table('synchronizations')
            ->where('name', RegionMarketOrders::name())
            ->delete();

        DB::table('synchronizations')
            ->where('name', StructureMarketOrders::name())
            ->delete();
    }
};
