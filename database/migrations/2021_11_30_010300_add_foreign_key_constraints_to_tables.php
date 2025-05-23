<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // Commented out so foreign keys are not added on new installations.
        // For existing installations, another migration has been added to drop all foreign keys.
        //        Schema::table('igniterlabs_webhook_logs', function (Blueprint $table) {
        //            $table->foreignId('webhook_id')->nullable()->change();
        //        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void {}
};
