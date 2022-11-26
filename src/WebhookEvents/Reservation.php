<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Admin\Models\StatusHistory;
use IgniterLabs\Webhook\Classes\BaseEvent;

class Reservation extends BaseEvent
{
    protected $setupPartial = 'igniterlabs.webhook::_partials.setup.reservation';

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
            'created' => 'eloquent.created: Igniter\Admin\Models\Reservation',
            'updated' => 'eloquent.updated: Igniter\Admin\Models\Reservation',
            'status_added' => 'eloquent.created: Igniter\Admin\Models\StatusHistory',
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: Igniter\Admin\Models\Reservation',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $reservation = array_get($args, 0);
        if ($reservation instanceof StatusHistory)
            $reservation = $reservation->object;

        if (!$reservation instanceof Reservation)
            return;

        return [
            'reservation' => $reservation->toArray(),
        ];
    }
}
