<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('igniterlabs_webhook_logs', function(Blueprint $table): void {
            $table->dropForeignKeyIfExists('webhook_id');
            $table->dropIndexIfExists('igniterlabs_webhook_logs_webhook_id_foreign');
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void {}
};
