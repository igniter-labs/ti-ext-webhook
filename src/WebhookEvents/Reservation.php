<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Admin\Models\StatusHistory;
use Igniter\Reservation\Models\Reservation as ReservationModel;
use IgniterLabs\Webhook\Classes\BaseEvent;
use Override;

class Reservation extends BaseEvent
{
    protected $setupPartial = 'igniterlabs.webhook::_partials.setup.reservation';

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function eventDetails(): array
    {
        return [
            'name' => 'Reservations',
            'description' => 'Reservation created, updated or deleted.',
        ];
    }

    #[Override]
    public static function registerEventListeners(): array
    {
        return [
            'created' => 'eloquent.created: '.ReservationModel::class,
            'updated' => 'eloquent.updated: '.ReservationModel::class,
            'status_added' => 'eloquent.created: '.StatusHistory::class,
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: '.ReservationModel::class,
        ];
    }

    #[Override]
    public static function makePayloadFromEvent(array $args, $actionCode = null): ?array
    {
        $reservation = array_get($args, 0);
        if ($reservation instanceof StatusHistory) {
            $reservation = $reservation->object;
        }

        if (!$reservation instanceof ReservationModel) {
            return null;
        }

        return [
            'reservation' => $reservation->toArray(),
        ];
    }
}
