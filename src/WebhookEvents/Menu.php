<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Igniter\Cart\Models\Menu as MenuModel;
use IgniterLabs\Webhook\Classes\BaseEvent;

class Menu extends BaseEvent
{
    protected $setupPartial = 'igniterlabs.webhook::_partials.setup.menu';

    /**
     * {@inheritdoc}
     */
    public function eventDetails()
    {
        return [
            'name' => 'Menu Items',
            'description' => 'Menu item created, updated, stock updated (after checkout) or deleted.',
        ];
    }

    public static function registerEventListeners()
    {
        return [
            'created' => 'eloquent.created: '.MenuModel::class,
            'updated' => 'eloquent.updated: '.MenuModel::class,
            'stock_updated' => 'admin.menu.stockUpdated',
            'deleted' => 'eloquent.deleted: '.MenuModel::class,
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $menu = array_get($args, 0);
        if (!$menu instanceof MenuModel) {
            return;
        }

        return [
            'menu' => $menu->toArray(),
        ];
    }
}
