<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Admin\Models\Orders_model;
use Admin\Models\Status_history_model;
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
            'created' => 'eloquent.created: Admin\Models\Orders_model',
            'updated' => 'eloquent.updated: Admin\Models\Orders_model',
            'placed' => 'admin.order.paymentProcessed',
            'status_added' => 'eloquent.created: Admin\Models\Status_history_model',
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: Admin\Models\Orders_model',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $order = array_get($args, 0);
        if ($order instanceof Status_history_model)
            $order = $order->object;

        if (!$order instanceof Orders_model)
            return;

        $options = self::getMenuOptions($order);
        return [
            'order' => $order->toArray() + ['menu_options' => $options],
        ];
    }
    public static function getMenuOptions($order)
    {
        $items = [];
        foreach ($order->getOrderMenusWithOptions() as $orderItem) {
            $itemOptionGroup = $orderItem->menu_options->groupBy('order_option_category');
            if ($itemOptionGroup->isNotEmpty()) {
                $options = [];
                foreach ($itemOptionGroup as $itemOptionGroupName => $itemOptions) {
                    if ($itemOptionGroup->isNotEmpty()) {
                        $options2 = [];
                        foreach ($itemOptions as $itemOption) {
                            $options2[] = [
                                'name' => $itemOption->order_option_name,
                                'unit_price' => $itemOption->order_option_price,
                                'quantity' => $itemOption->quantity,
                                'subtotal' => currency_format($itemOption->quantity * $itemOption->order_option_price),
                            ];
                        }
                        $options[] = ['name' => $itemOptionGroupName, 'details' => $options2];
                    }
                }
            }
            $items[] = ['name' => $orderItem->name, 'subtotal' => $orderItem->subtotal, 'quantity' => $orderItem->quantity, 'options' => $options];
        }
        return $items;
    }
}
