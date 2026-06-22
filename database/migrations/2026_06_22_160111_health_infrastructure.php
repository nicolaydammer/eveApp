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
        Schema::create('health_events', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('source');
            $table->string('exception');

            $table->json('context')->nullable();

            $table->unsignedInteger('occurrences')->default(1);

            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at');

            $table->timestamps();

            $table->index('source');
            $table->index('last_seen_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_events');
    }
};
