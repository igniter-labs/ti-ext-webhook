<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Admin\Models\StatusHistory;
use Igniter\Cart\Models\Order as OrderModel;
use IgniterLabs\Webhook\Classes\BaseEvent;
use Override;

class Order extends BaseEvent
{
    protected $setupPartial = 'igniterlabs.webhook::_partials.setup.order';

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function eventDetails(): array
    {
        return [
            'name' => 'Orders',
            'description' => 'Order created, status added, assigned or deleted.',
        ];
    }

    #[Override]
    public static function registerEventListeners(): array
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

    #[Override]
    public static function makePayloadFromEvent(array $args, $actionCode = null): ?array
    {
        $order = array_get($args, 0);
        if ($order instanceof StatusHistory) {
            $order = $order->object;
        }

        if (!$order instanceof OrderModel) {
            return null;
        }

        return [
            'order' => $order->toArray(),
            'order_menus' => $order->getOrderMenusWithOptions()->toArray(),
            'order_totals' => $order->getOrderTotals()->toArray(),
        ];
    }
}
