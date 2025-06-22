<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Admin\Models\StatusHistory;
use Igniter\Cart\Models\Order as OrderModel;
use IgniterLabs\Webhook\Classes\BaseEvent;
use Override;

class Order extends BaseEvent
{
    protected string $setupPartial = 'igniterlabs.webhook::_partials.setup.order';

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
            'status_added' => 'admin.statusHistory.added',
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: '.OrderModel::class,
        ];
    }

    #[Override]
    public static function makePayloadFromEvent(array $args, $actionCode = null): ?array
    {
        $order = array_get($args, 0);
        if (in_array($actionCode, ['status_added', 'assigned']) && !$order instanceof OrderModel) {
            return null;
        }

        $payload = [];
        if ($order instanceof OrderModel) {
            $payload['order'] = $order->toArray();
            $payload['order_menus'] = $order->getOrderMenusWithOptions()->toArray();
            $payload['order_totals'] = $order->getOrderTotals()->toArray();
        }

        $statusHistory = array_get($args, 1);
        if ($statusHistory instanceof StatusHistory) {
            $payload['status_history'] = $statusHistory->toArray();
        }

        return $payload;
    }
}
