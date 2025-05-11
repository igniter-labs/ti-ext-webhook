<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('igniterlabs_webhook_outgoing', function(Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->text('events');
            $table->text('config_data')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });

        Schema::create('igniterlabs_webhook_logs', function(Blueprint $table): void {
            $table->bigIncrements('id');
            $table->bigInteger('uuid')->unsigned();
            $table->integer('webhook_id')->nullable()->unsigned()->index();
            $table->string('webhook_type')->nullable()->index();
            $table->string('name');
            $table->boolean('is_success')->default(0);
            $table->text('payload')->nullable();
            $table->text('response')->nullable();
            $table->text('exception')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('igniterlabs_webhook_outgoing');
        Schema::dropIfExists('igniterlabs_webhook_logs');
    }
};
