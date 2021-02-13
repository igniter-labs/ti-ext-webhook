<?php

namespace IgniterLabs\Webhook\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMessageColumnToLogsTable extends Migration
{
    public function up()
    {
        Schema::table('igniterlabs_webhook_logs', function (Blueprint $table) {
            $table->string('message', 255)->after('name');
            $table->dropColumn('uuid');
            $table->dropColumn('exception');
        });
    }

    public function down()
    {
    }
}
