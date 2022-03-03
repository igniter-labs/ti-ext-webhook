<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Admin\Models\Reservations_model;
use Admin\Models\Status_history_model;
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
            'description' => 'Reservation created, updated or deleted.',
        ];
    }

    public static function registerEventListeners()
    {
        return [
            'created' => 'eloquent.created: Admin\Models\Reservations_model',
            'updated' => 'eloquent.updated: Admin\Models\Reservations_model',
            'status_added' => 'eloquent.created: Admin\Models\Status_history_model',
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: Admin\Models\Reservations_model',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $reservation = array_get($args, 0);
        if ($reservation instanceof Status_history_model)
            $reservation = $reservation->object;

        if (!$reservation instanceof Reservations_model)
            return;

        return [
            'reservation' => $reservation->toArray(),
        ];
    }
}
