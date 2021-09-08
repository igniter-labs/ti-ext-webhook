<?php

namespace IgniterLabs\Webhook\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventCodeColumnToLogsTable extends Migration
{
    public function up()
    {
        Schema::table('igniterlabs_webhook_logs', function (Blueprint $table) {
            $table->text('event_code')->nullable();
        });
    }

    public function down()
    {
    }
}
