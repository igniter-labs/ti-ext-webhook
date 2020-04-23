<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use IgniterLabs\Webhook\Classes\BaseEvent;

class Order extends BaseEvent
{
    /**
     * @inheritDoc
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
            'updated' => 'eloquent.saved: Admin\Models\Orders_model',
            'status_added' => 'eloquent.created: Admin\Models\Status_history_model',
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: Admin\Models\Orders_model',
        ];
    }
}