<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use IgniterLabs\Webhook\Classes\BaseEvent;

class Category extends BaseEvent
{
    /**
     * {@inheritdoc}
     */
    public function eventDetails()
    {
        return [
            'name' => 'Categories',
            'description' => 'Category created, updated or deleted.',
        ];
    }

    public static function registerEventListeners()
    {
        return [
            'created' => 'eloquent.created: Igniter\Admin\Models\Category',
            'updated' => 'eloquent.updated: Igniter\Admin\Models\Category',
            'deleted' => 'eloquent.deleted: Igniter\Admin\Models\Category',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $category = array_get($args, 0);
        if (!$category instanceof Category)
            return;

        return [
            'category' => $category->toArray(),
        ];
    }
}
