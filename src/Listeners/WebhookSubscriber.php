<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Listeners;

use IgniterLabs\Webhook\Models\WebhookLog;
use Illuminate\Events\Dispatcher;
use Spatie\WebhookServer\Events\FinalWebhookCallFailedEvent;
use Spatie\WebhookServer\Events\WebhookCallSucceededEvent;

class WebhookSubscriber
{
    public function handleSucceeded(WebhookCallSucceededEvent $event): void
    {
        WebhookLog::createLog($event);
    }

    public function handleFailed(FinalWebhookCallFailedEvent $event): void
    {
        WebhookLog::createLog($event);
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(WebhookCallSucceededEvent::class, $this->handleSucceeded(...));
        $events->listen(FinalWebhookCallFailedEvent::class, $this->handleFailed(...));
    }
}
