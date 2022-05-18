<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use IgniterLabs\Webhook\Classes\BaseEvent;

class Table extends BaseEvent
{
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
            'created' => 'eloquent.created: Igniter\Admin\Models\Table',
            'updated' => 'eloquent.updated: Igniter\Admin\Models\Table',
            'deleted' => 'eloquent.deleted: Igniter\Admin\Models\Table',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $table = array_get($args, 0);
        if (!$table instanceof Table)
            return;

        return [
            'table' => $table->toArray(),
        ];
    }
}
