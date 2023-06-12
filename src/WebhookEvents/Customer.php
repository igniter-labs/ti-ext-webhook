<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use IgniterLabs\Webhook\Classes\BaseEvent;

class Customer extends BaseEvent
{
    protected $setupPartial = 'igniterlabs.webhook::_partials.setup.customer';

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
            'created' => 'eloquent.created: Igniter\User\Models\Customer',
            'updated' => 'eloquent.updated: Igniter\User\Models\Customer',
            'deleted' => 'eloquent.deleted: Igniter\User\Models\Customer',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $customer = array_get($args, 0);
        if (!$customer instanceof Customer) {
            return;
        }

        return [
            'customer' => $customer->toArray(),
        ];
    }
}
