<?php

namespace IgniterLabs\Webhook\Tests\WebhookEvents;

use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Tests\TestCase;
use IgniterLabs\Webhook\WebhookEvents\Order;

class CategoryTest extends TestCase
{
    public function testCategoryCreatedEvent()
    {
        $actionCode = 'created';
        $payload = Order::makePayloadFromEvent([], $actionCode);
        resolve(WebhookManager::class)->runWebhookEvent(
            'order', $actionCode, $payload
        );
    }

    public function testCategoryUpdatedEvent()
    {
    }
}
