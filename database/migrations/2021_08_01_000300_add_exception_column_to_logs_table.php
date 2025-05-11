<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('igniterlabs_webhook_logs', function(Blueprint $table): void {
            $table->text('exception')->nullable();
        });
    }

    public function down(): void {}
};
