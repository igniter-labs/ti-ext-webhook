<?php

namespace IgniterLabs\Webhook\WebhookActions;

use IgniterLabs\Webhook\Classes\BaseAction;

class Order extends BaseAction
{
    /**
     * @inheritDoc
     */
    public function actionDetails()
    {
        return [
            'name' => 'Orders',
            'description' => 'Update status, assign or delete an order.',
        ];
    }

    public function registerEntryPoints()
    {
        return [
            'update' => 'processUpdateAction',
            'assign' => 'processAssignAction',
            'delete' => 'processDeleteAction',
        ];
    }
}