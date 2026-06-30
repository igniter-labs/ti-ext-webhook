<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Override;
use Spatie\WebhookServer\CallWebhookJob;

class SafeCallWebhookJob extends CallWebhookJob
{
    #[Override]
    protected function getClient(): ClientInterface
    {
        return new Client([
            'allow_redirects' => false,
        ]);
    }
}
