<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Admin\Models\StatusHistory;
use Igniter\Cart\Models\Order as OrderModel;
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
            'created' => 'eloquent.created: '.OrderModel::class,
            'updated' => 'eloquent.updated: '.OrderModel::class,
            'placed' => 'admin.order.paymentProcessed',
            'status_added' => 'eloquent.created: '.StatusHistory::class,
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: '.OrderModel::class,
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $order = array_get($args, 0);
        if ($order instanceof StatusHistory) {
            $order = $order->object;
        }

        if (!$order instanceof OrderModel) {
            return;
        }

        return [
            'order' => $order->toArray(),
            'order_menus' => $order->getOrderMenusWithOptions()->toArray(),
            'order_totals' => $order->getOrderTotals()->toArray(),
        ];
    }
}
