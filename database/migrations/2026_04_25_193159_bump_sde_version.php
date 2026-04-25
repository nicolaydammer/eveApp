<?php

use App\Domain\SDE\Services\State\VersionRepository;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        (new VersionRepository)->setSupportedVersion(3316380);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
