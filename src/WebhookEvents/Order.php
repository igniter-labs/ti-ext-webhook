<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Admin\Models\StatusHistory;
use IgniterLabs\Webhook\Classes\BaseEvent;

class Order extends BaseEvent
{
    protected $setupPartial = 'igniterlabs.webhook::_partials.setup.order';

    /**
     * {@inheritdoc}
     */
    public function eventDetails()
    {
        return [
            'name' => 'Orders',
            'description' => 'Order created, status added, assigned or deleted.',
        ];
    }

    public static function registerEventListeners()
    {
        return [
            'created' => 'eloquent.created: Igniter\Admin\Models\Order',
            'updated' => 'eloquent.updated: Igniter\Admin\Models\Order',
            'placed' => 'admin.order.paymentProcessed',
            'status_added' => 'eloquent.created: Igniter\Admin\Models\StatusHistory',
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: Igniter\Admin\Models\Order',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $order = array_get($args, 0);
        if ($order instanceof StatusHistory) {
            $order = $order->object;
        }

        if (!$order instanceof Order) {
            return;
        }

        return [
            'order' => $order->toArray(),
        ];
    }
}
