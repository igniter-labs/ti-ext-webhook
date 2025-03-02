<?php

namespace IgniterLabs\Webhook\Console;

use IgniterLabs\Webhook\Models\WebhookLog;
use Illuminate\Console\Command;

class Cleanup extends Command
{
    protected $signature = 'webhook:cleanup';

    protected $description = 'Clean up old records from the webhook log.';

    public static $logTTL = 365 / 4; // prune logs older than 3 months

    public function handle()
    {
        $this->comment('Cleaning stale webhook log...');
        $logTTL = now()->subDays(config('igniter.system.deleteOldRecordsDays', static::$logTTL))->format('Y-m-d H:i:s');

        $amountDeleted = WebhookLog::where('created_at', '<', $logTTL)->delete();

        $this->info("Deleted {$amountDeleted} record(s) from the webhook log.");
        $this->comment('All done!');
    }
}
