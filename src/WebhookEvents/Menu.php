<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use IgniterLabs\Webhook\Classes\BaseEvent;

class Menu extends BaseEvent
{
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
            'created' => 'eloquent.created: Igniter\Admin\Models\Menu',
            'updated' => 'eloquent.updated: Igniter\Admin\Models\Menu',
            'stock_updated' => 'admin.menu.stockUpdated',
            'deleted' => 'eloquent.deleted: Igniter\Admin\Models\Menu',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $menu = array_get($args, 0);
        if (!$menu instanceof Menu)
            return;

        return [
            'menu' => $menu->toArray(),
        ];
    }
}
