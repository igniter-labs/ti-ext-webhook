<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\User\Models\Customer as CustomerModel;
use IgniterLabs\Webhook\Classes\BaseEvent;
use Override;

class Customer extends BaseEvent
{
    protected $setupPartial = 'igniterlabs.webhook::_partials.setup.customer';

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function eventDetails(): array
    {
        return [
            'name' => 'Customers',
            'description' => 'Customer created, updated or deleted.',
            'setup' => '$/igniterlabs/webhook/webhookevents/customer/setup.md',
        ];
    }

    #[Override]
    public static function registerEventListeners(): array
    {
        return [
            'created' => 'eloquent.created: '.CustomerModel::class,
            'updated' => 'eloquent.updated: '.CustomerModel::class,
            'deleted' => 'eloquent.deleted: '.CustomerModel::class,
        ];
    }

    #[Override]
    public static function makePayloadFromEvent(array $args, $actionCode = null): ?array
    {
        $customer = array_get($args, 0);
        if (!$customer instanceof CustomerModel) {
            return null;
        }

        return [
            'customer' => $customer->toArray(),
        ];
    }
}
