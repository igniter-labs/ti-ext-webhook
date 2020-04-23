<?php

namespace IgniterLabs\Webhook\WebhookActions;

use IgniterLabs\Webhook\Classes\BaseAction;

class Location extends BaseAction
{
    /**
     * @inheritDoc
     */
    public function actionDetails()
    {
        return [
            'name' => 'Locations',
            'description' => 'Create, update or delete a location.',
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
}