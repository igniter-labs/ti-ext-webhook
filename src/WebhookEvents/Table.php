<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Reservation\Models\DiningTable;
use IgniterLabs\Webhook\Classes\BaseEvent;
use Override;

class Table extends BaseEvent
{
    protected $setupPartial = 'igniterlabs.webhook::_partials.setup.table';

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function eventDetails(): array
    {
        return [
            'name' => 'Tables',
            'description' => 'Table created, updated or deleted.',
        ];
    }

    #[Override]
    public static function registerEventListeners(): array
    {
        return [
            'created' => 'eloquent.created: '.DiningTable::class,
            'updated' => 'eloquent.updated: '.DiningTable::class,
            'deleted' => 'eloquent.deleted: '.DiningTable::class,
        ];
    }

    #[Override]
    public static function makePayloadFromEvent(array $args, $actionCode = null): ?array
    {
        $table = array_get($args, 0);
        if (!$table instanceof DiningTable) {
            return null;
        }

        return [
            'table' => $table->toArray(),
        ];
    }
}
