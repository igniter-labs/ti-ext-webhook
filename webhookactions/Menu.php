<?php

namespace IgniterLabs\Webhook\WebhookActions;

use IgniterLabs\Webhook\Classes\BaseAction;

class Menu extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    public function actionDetails()
    {
        return [
            'name' => 'Menu Items',
            'description' => 'Create, update or delete a menu item.',
        ];
    }

    public function registerEntryPoints()
    {
        return [
            'create' => 'processCreateAction',
            'update' => 'processUpdateAction',
            'delete' => 'processDeleteAction',
        ];
    }

    //
    // Actions
    //

    public function processListAction()
    {

    }
}
