<?php

namespace IgniterLabs\Webhook\Tests\WebhookEvents;

use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Models\Outgoing;
use IgniterLabs\Webhook\WebhookEvents\Category;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

it('runs category webhook event', function ($action) {
    Queue::fake();
    Event::fake();

    Outgoing::create([
        'name' => 'Category created',
        'url' => 'http://webhook.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    $actionCode = 'created';
    $payload = Category::makePayloadFromEvent([], $actionCode);
    resolve(WebhookManager::class)->runWebhookEvent(
        'category', $actionCode, $payload ?? []
    );

    Event::assertDispatched('igniterlabs.webhook.beforeDispatch');
})->with([
    'created',
]);
