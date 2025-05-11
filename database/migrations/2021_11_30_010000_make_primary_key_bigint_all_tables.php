<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'igniterlabs_webhook_logs' => 'id',
            'igniterlabs_webhook_outgoing' => 'id',
        ] as $table => $key) {
            Schema::table($table, function(Blueprint $table) use ($key): void {
                $table->unsignedBigInteger($key, true)->change();
            });
        }
    }

    public function down(): void {}
};
