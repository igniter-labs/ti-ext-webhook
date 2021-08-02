<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Admin\Models\Tables_model;
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
            'created' => 'eloquent.created: Admin\Models\Tables_model',
            'updated' => 'eloquent.updated: Admin\Models\Tables_model',
            'deleted' => 'eloquent.deleted: Admin\Models\Tables_model',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $table = array_get($args, 0);
        if (!$table instanceof Tables_model)
            return;

        return [
            'table' => $table->toArray(),
        ];
    }
}
