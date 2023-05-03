<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        foreach ([
            'igniterlabs_webhook_logs' => 'id',
            'igniterlabs_webhook_outgoing' => 'id',
        ] as $table => $key) {
            Schema::table($table, function (Blueprint $table) use ($key) {
                $table->unsignedBigInteger($key, true)->change();
            });
        }
    }

    public function down()
    {
    }
};
