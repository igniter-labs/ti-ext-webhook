<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Cart\Models\Menu as MenuModel;
use IgniterLabs\Webhook\Classes\BaseEvent;
use Override;

class Menu extends BaseEvent
{
    protected string $setupPartial = 'igniterlabs.webhook::_partials.setup.menu';

    /**
     * {@inheritdoc}
     */
    #[Override]
    public function eventDetails(): array
    {
        return [
            'name' => 'Menu Items',
            'description' => 'Menu item created, updated, stock updated (after checkout) or deleted.',
        ];
    }

    #[Override]
    public static function registerEventListeners(): array
    {
        return [
            'created' => 'eloquent.created: '.MenuModel::class,
            'updated' => 'eloquent.updated: '.MenuModel::class,
            'stock_updated' => 'admin.menu.stockUpdated',
            'deleted' => 'eloquent.deleted: '.MenuModel::class,
        ];
    }

    #[Override]
    public static function makePayloadFromEvent(array $args, $actionCode = null): ?array
    {
        $menu = array_get($args, 0);
        if (!$menu instanceof MenuModel) {
            return null;
        }

        return [
            'menu' => $menu->toArray(),
        ];
    }
}
