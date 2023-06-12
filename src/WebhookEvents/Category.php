<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use IgniterLabs\Webhook\Classes\BaseEvent;

class Category extends BaseEvent
{
    protected $setupPartial = 'igniterlabs.webhook::_partials.setup.category';

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
            'created' => 'eloquent.created: Igniter\Cart\Models\Category',
            'updated' => 'eloquent.updated: Igniter\Cart\Models\Category',
            'deleted' => 'eloquent.deleted: Igniter\Cart\Models\Category',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $category = array_get($args, 0);
        if (!$category instanceof Category) {
            return;
        }

        return [
            'category' => $category->toArray(),
        ];
    }
}
