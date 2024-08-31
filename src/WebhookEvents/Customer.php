<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\User\Models\Customer as CustomerModel;
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
            'created' => 'eloquent.created: '.CustomerModel::class,
            'updated' => 'eloquent.updated: '.CustomerModel::class,
            'deleted' => 'eloquent.deleted: '.CustomerModel::class,
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $customer = array_get($args, 0);
        if (!$customer instanceof CustomerModel) {
            return;
        }

        return [
            'customer' => $customer->toArray(),
        ];
    }
}
