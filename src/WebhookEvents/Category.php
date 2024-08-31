<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Cart\Models\Category as CategoryModel;
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
            'created' => 'eloquent.created: '.CategoryModel::class,
            'updated' => 'eloquent.updated: '.CategoryModel::class,
            'deleted' => 'eloquent.deleted: '.CategoryModel::class,
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $category = array_get($args, 0);
        if (!$category instanceof CategoryModel) {
            return;
        }

        return [
            'category' => $category->toArray(),
        ];
    }
}
