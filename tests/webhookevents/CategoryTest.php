<?php

namespace IgniterLabs\Webhook\Tests\WebhookEvents;

use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\WebhookEvents\Order;
use Tests\ExtensionTestCase;

class CategoryTest extends ExtensionTestCase
{
    public function testCategoryCreatedEvent()
    {
        $actionCode = 'created';
        $payload = Order::makePayloadFromEvent([], $actionCode);
        WebhookManager::instance()->runWebhookEvent(
            'order', $actionCode, $payload
        );
    }

    public function testCategoryUpdatedEvent()
    {
    }
}
