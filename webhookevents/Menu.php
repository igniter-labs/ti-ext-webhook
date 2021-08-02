<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use Admin\Models\Menus_model;
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
            'created' => 'eloquent.created: Admin\Models\Menus_model',
            'updated' => 'eloquent.updated: Admin\Models\Menus_model',
            'stock_updated' => 'admin.menu.stockUpdated',
            'deleted' => 'eloquent.deleted: Admin\Models\Menus_model',
        ];
    }

    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        $menu = array_get($args, 0);
        if (!$menu instanceof Menus_model)
            return;

        return [
            'menu' => $menu->toArray(),
        ];
    }
}
