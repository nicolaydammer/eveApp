<?php

use App\Domain\SDE\Services\State\VersionRepository;
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
        (new VersionRepository())->setSupportedVersion(3433564);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
