<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use IgniterLabs\Webhook\Classes\BaseEvent;

class Location extends BaseEvent
{
    /**
     * @inheritDoc
     */
    public function eventDetails()
    {
        return [
            'name' => 'Locations',
            'description' => 'Location created, updated or deleted.',
        ];
    }

    public static function registerEventListeners()
    {
        return [
            'created' => 'eloquent.created: Admin\Models\Locations_model',
            'updated' => 'eloquent.saved: Admin\Models\Locations_model',
            'deleted' => 'eloquent.deleted: Admin\Models\Locations_model',
        ];
    }
}