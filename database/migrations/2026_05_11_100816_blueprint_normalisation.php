<?php

use App\Domain\SDE\Services\State\VersionRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sde.blueprints', function (Blueprint $table) {
            $table->dropColumn('activities');
            $table->bigInteger('copy_time')->default(0);
            $table->bigInteger('research_time')->default(0);
            $table->bigInteger('material_time')->default(0);
        });

        Schema::create('sde.blueprints_manufacturing', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blueprintID')->unique();
            $table->bigInteger('time');
        });

        Schema::create('sde.blueprints_manufacturing_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blueprints_manufacturing_id');
            $table->unsignedBigInteger('typeID');
            $table->unsignedBigInteger('quantity');

            $table->unique(['blueprints_manufacturing_id', 'typeID']);
        });

        Schema::create('sde.blueprints_manufacturing_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blueprints_manufacturing_id');
            $table->unsignedBigInteger('typeID');
            $table->unsignedBigInteger('quantity');

            $table->unique(['blueprints_manufacturing_id', 'typeID']);
        });

        Schema::create('sde.blueprints_manufacturing_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blueprints_manufacturing_id');
            $table->integer('level');
            $table->unsignedBigInteger('typeID');

            $table->unique(['blueprints_manufacturing_id', 'typeID']);
        });

        Schema::create('sde.blueprints_reaction', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blueprintID')->unique();
            $table->bigInteger('time');
        });

        Schema::create('sde.blueprints_reaction_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blueprints_reaction_id');
            $table->integer('level');
            $table->unsignedBigInteger('typeID');

            $table->unique(['blueprints_reaction_id', 'typeID']);
        });

        Schema::create('sde.blueprints_reaction_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blueprints_reaction_id');
            $table->unsignedBigInteger('typeID');
            $table->unsignedBigInteger('quantity');

            $table->unique(['blueprints_reaction_id', 'typeID']);
        });

        Schema::create('sde.blueprints_reaction_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blueprints_reaction_id');
            $table->unsignedBigInteger('typeID');
            $table->unsignedBigInteger('quantity');

            $table->unique(['blueprints_reaction_id', 'typeID']);
        });

        Schema::create('sde.blueprints_invention', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('blueprintID')->unique();
            $table->bigInteger('time');
        });

        Schema::create('sde.blueprints_invention_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blueprints_invention_id');
            $table->integer('level');
            $table->unsignedBigInteger('typeID');

            $table->unique(['blueprints_invention_id', 'typeID']);
        });

        Schema::create('sde.blueprints_invention_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blueprints_invention_id');
            $table->unsignedBigInteger('typeID');
            $table->unsignedBigInteger('quantity');
            $table->double('probability')->nullable();

            $table->unique(['blueprints_invention_id', 'typeID']);
        });

        Schema::create('sde.blueprints_invention_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blueprints_invention_id');
            $table->unsignedBigInteger('typeID');
            $table->unsignedBigInteger('quantity');

            $table->unique(['blueprints_invention_id', 'typeID']);
        });

        (new VersionRepository())->setSupportedVersion(3346029);
    }

    public function down(): void
    {
        Schema::table('sde.blueprints', function (Blueprint $table) {
            $table->jsonb('activities')->nullable();
            $table->dropColumn('copy_time');
            $table->dropColumn('research_time');
            $table->dropColumn('material_time');
        });

        Schema::dropIfExists('sde.blueprints_invention_skills');
        Schema::dropIfExists('sde.blueprints_invention_products');
        Schema::dropIfExists('sde.blueprints_invention_materials');

        Schema::dropIfExists('sde.blueprints_manufacturing_skills');
        Schema::dropIfExists('sde.blueprints_manufacturing_products');
        Schema::dropIfExists('sde.blueprints_manufacturing_materials');

        Schema::dropIfExists('sde.blueprints_reaction_skills');
        Schema::dropIfExists('sde.blueprints_reaction_products');
        Schema::dropIfExists('sde.blueprints_reaction_materials');

        Schema::dropIfExists('sde.blueprints_invention');
        Schema::dropIfExists('sde.blueprints_manufacturing');
        Schema::dropIfExists('sde.blueprints_reaction');
    }
};
