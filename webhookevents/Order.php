<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Admin\Models\Orders_model;
use IgniterLabs\Webhook\Classes\BaseEvent;

class Order extends BaseEvent
{
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
            'created' => 'admin.order.paymentProcessed',
            'updated' => 'eloquent.saved: Admin\Models\Orders_model',
            'status_added' => 'eloquent.created: Admin\Models\Status_history_model',
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: Admin\Models\Orders_model',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $order = array_get($args, 0);
        if (!$order instanceof Orders_model)
            return;

        return [
            'order' => $order->toArray(),
        ];
    }
}
