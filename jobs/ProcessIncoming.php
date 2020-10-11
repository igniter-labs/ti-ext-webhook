<?php

namespace IgniterLabs\Webhook\Jobs;

use Spatie\WebhookClient\Exceptions\WebhookFailed;
use Spatie\WebhookClient\ProcessWebhookJob;

class ProcessIncoming extends ProcessWebhookJob
{
    public function handle()
    {
        if (!$incoming = $this->webhookCall->webhook)
            throw new WebhookFailed('Incoming webhook record not found.');

        $entryPoints = $incoming->registerEntryPoints();
        $entryPoint = array_get($this->webhookCall->payload ?? [], 'action');

        if ($methodName = array_get($entryPoints, $entryPoint))
            $incoming->{$methodName}($this->webhookCall, $entryPoint);

        $this->webhookCall->markAsSuccessful();
    }
}
