<?php

namespace IgniterLabs\Webhook\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropForeignKeyConstraints extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('igniterlabs_webhook_logs', function (Blueprint $table) {
            $table->dropForeignKeyIfExists('webhook_id');
            $table->dropIndexIfExists(sprintf('%s%s_%s_foreign', DB::getTablePrefix(), 'igniterlabs_webhook_logs', 'webhook_id'));
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
    }
}
