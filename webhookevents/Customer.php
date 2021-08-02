<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Admin\Models\Customers_model;
use IgniterLabs\Webhook\Classes\BaseEvent;

class Customer extends BaseEvent
{
    /**
     * {@inheritdoc}
     */
    public function eventDetails()
    {
        return [
            'name' => 'Customers',
            'description' => 'Customer created, updated or deleted.',
            'setup' => '$/igniterlabs/webhook/webhookevents/customer/setup.md',
        ];
    }

    public static function registerEventListeners()
    {
        return [
            'created' => 'eloquent.created: Admin\Models\Customers_model',
            'updated' => 'eloquent.updated: Admin\Models\Customers_model',
            'deleted' => 'eloquent.deleted: Admin\Models\Customers_model',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $customer = array_get($args, 0);
        if (!$customer instanceof Customers_model)
            return;

        return [
            'customer' => $customer->toArray(),
        ];
    }
}
