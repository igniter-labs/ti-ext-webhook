<?php

namespace Igniter\Automation\Console;

use IgniterLabs\Webhook\Models\WebhookLog;
use Illuminate\Console\Command;

class Cleanup extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'webhook:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old records from the webhook log.';

    public static $logTTL = 365 / 4; // prune logs older than 3 months

    public function handle()
    {
        $this->comment('Cleaning old automation log...');
        $logTTL = now()->subDays(config('igniter.system.deleteOldRecordsDays', static::$logTTL))->format('Y-m-d H:i:s');

        $amountDeleted = WebhookLog::where('created_at', '<', $logTTL)->delete();

        $this->info("Deleted {$amountDeleted} record(s) from the automation log.");
        $this->comment('All done!');
    }
}
