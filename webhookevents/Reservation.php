<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use IgniterLabs\Webhook\Classes\BaseEvent;

class Reservation extends BaseEvent
{
    /**
     * {@inheritdoc}
     */
    public function eventDetails()
    {
        return [
            'name' => 'Reservations',
            'description' => 'Reservation created, status added, assigned or deleted.',
        ];
    }

    public static function registerEventListeners()
    {
        return [
            'created' => 'eloquent.created: Admin\Models\Reservations_model',
            'updated' => 'eloquent.saved: Admin\Models\Reservations_model',
            'status_added' => 'eloquent.created: Admin\Models\Status_history_model',
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: Admin\Models\Reservations_model',
        ];
    }
}
