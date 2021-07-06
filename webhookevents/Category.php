<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Admin\Models\Categories_model;
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
            'created' => 'eloquent.created: Admin\Models\Categories_model',
            'updated' => 'eloquent.updated: Admin\Models\Categories_model',
            'deleted' => 'eloquent.deleted: Admin\Models\Categories_model',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $category = array_get($args, 0);
        if (!$category instanceof Categories_model)
            return;

        return [
            'category' => $category->toArray(),
        ];
    }
}
