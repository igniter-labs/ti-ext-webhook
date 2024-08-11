<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('igniterlabs_webhook_logs', function(Blueprint $table) {
            $table->text('exception')->nullable();
        });
    }

    public function down() {}
};
