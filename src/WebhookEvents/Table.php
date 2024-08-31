<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Reservation\Models\DiningTable;
use IgniterLabs\Webhook\Classes\BaseEvent;

class Table extends BaseEvent
{
    protected $setupPartial = 'igniterlabs.webhook::_partials.setup.table';

    /**
     * {@inheritdoc}
     */
    public function eventDetails()
    {
        return [
            'name' => 'Tables',
            'description' => 'Table created, updated or deleted.',
        ];
    }

    public static function registerEventListeners()
    {
        return [
            'created' => 'eloquent.created: '.DiningTable::class,
            'updated' => 'eloquent.updated: '.DiningTable::class,
            'deleted' => 'eloquent.deleted: '.DiningTable::class,
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $table = array_get($args, 0);
        if (!$table instanceof DiningTable) {
            return;
        }

        return [
            'table' => $table->toArray(),
        ];
    }
}
