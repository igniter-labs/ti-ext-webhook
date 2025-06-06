<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Admin\Models\StatusHistory;
use Igniter\Reservation\Models\Reservation as ReservationModel;
use IgniterLabs\Webhook\Classes\BaseEvent;
use Override;

class Reservation extends BaseEvent
{
    protected string $setupPartial = 'igniterlabs.webhook::_partials.setup.reservation';

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
            'status_added' => 'admin.statusHistory.added',
            'assigned' => 'admin.assignable.assigned',
            'deleted' => 'eloquent.deleted: '.ReservationModel::class,
        ];
    }

    #[Override]
    public static function makePayloadFromEvent(array $args, $actionCode = null): ?array
    {
        $reservation = array_get($args, 0);
        if (in_array($actionCode, ['status_added', 'assigned']) && !$reservation instanceof ReservationModel) {
            return null;
        }

        $payload = [];
        if ($reservation instanceof ReservationModel) {
            $payload['reservation'] = $reservation->toArray();
        }

        $statusHistory = array_get($args, 1);
        if ($statusHistory instanceof StatusHistory) {
            $payload['status_history'] = $statusHistory->toArray();
        }

        return $payload;
    }
}
