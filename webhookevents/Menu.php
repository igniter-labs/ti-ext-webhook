<?php

namespace IgniterLabs\Webhook\WebhookEvents;

use IgniterLabs\Webhook\Classes\BaseEvent;

class Menu extends BaseEvent
{
    /**
     * @inheritDoc
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
            'updated' => 'eloquent.saved: Admin\Models\Menus_model',
            'stock_updated' => 'admin.menu.stockUpdated',
            'deleted' => 'eloquent.deleted: Admin\Models\Menus_model',
        ];
    }

    public function triggerAction($action)
    {

    }
}