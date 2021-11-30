<?php

namespace IgniterLabs\Webhook\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyConstraintsToTables extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('igniterlabs_webhook_logs', function (Blueprint $table) {
            $table->foreignId('webhook_id')->nullable()->change();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
    }
}
