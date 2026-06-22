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
        Schema::create('synchronizations', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->boolean('enabled')->default(true);

            $table->string('frequency');
            $table->unsignedSmallInteger('offset_minutes')->default(0);
            $table->boolean('downtime_aware')->default(true);

            $table->timestamps();
        });

        Schema::create('synchronization_states', function (Blueprint $table) {
            $table->id();

            $table->foreignId('synchronization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('status');

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamp('last_synced_at')->nullable();
            $table->timestamp('next_synced_at')->nullable();

            $table->unsignedInteger('expected_jobs')->default(0);
            $table->unsignedInteger('completed_jobs')->default(0);
            $table->unsignedInteger('failed_jobs')->default(0);

            $table->timestamps();

            $table->unique('synchronization_id');
        });

        Schema::create('synchronization_runs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('synchronization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('status');

            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();

            $table->unsignedInteger('expected_jobs')->default(0);
            $table->unsignedInteger('completed_jobs')->default(0);
            $table->unsignedInteger('failed_jobs')->default(0);

            $table->timestamps();

            $table->index('synchronization_id');
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('synchronization_runs');
        Schema::dropIfExists('synchronization_states');
        Schema::dropIfExists('synchronizations');
    }
};
