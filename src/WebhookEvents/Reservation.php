<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Admin\Models\StatusHistory;
use Igniter\Reservation\Models\Reservation as ReservationModel;
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
            'created' => 'eloquent.created: '.ReservationModel::class,
            'updated' => 'eloquent.updated: '.ReservationModel::class,
            'status_added' => 'eloquent.created: '.StatusHistory::class,
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: '.ReservationModel::class,
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $reservation = array_get($args, 0);
        if ($reservation instanceof StatusHistory) {
            $reservation = $reservation->object;
        }

        if (!$reservation instanceof ReservationModel) {
            return;
        }

        return [
            'reservation' => $reservation->toArray(),
        ];
    }
}
