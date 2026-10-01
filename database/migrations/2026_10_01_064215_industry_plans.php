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
        Schema::create('industry_plan', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->jsonb('plan');
            $table->timestamps();

            $table->unique(['user_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('industry_plan');
    }
};
