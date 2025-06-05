<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Cart\Models\Category as CategoryModel;
use IgniterLabs\Webhook\Classes\BaseEvent;
use Override;

class Category extends BaseEvent
{
    protected string $setupPartial = 'igniterlabs.webhook::_partials.setup.category';

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function eventDetails(): array
    {
        return [
            'name' => 'Categories',
            'description' => 'Category created, updated or deleted.',
        ];
    }

    #[Override]
    public static function registerEventListeners(): array
    {
        return [
            'created' => 'eloquent.created: '.CategoryModel::class,
            'updated' => 'eloquent.updated: '.CategoryModel::class,
            'deleted' => 'eloquent.deleted: '.CategoryModel::class,
        ];
    }

    #[Override]
    public static function makePayloadFromEvent(array $args, $actionCode = null): ?array
    {
        $category = array_get($args, 0);
        if (!$category instanceof CategoryModel) {
            return null;
        }

        return [
            'category' => $category->toArray(),
        ];
    }
}
