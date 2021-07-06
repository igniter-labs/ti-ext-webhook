<?php

namespace IgniterLabs\Webhook\WebhookActions;

use IgniterLabs\Webhook\Classes\BaseAction;

class Table extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    public function actionDetails()
    {
        return [
            'name' => 'Tables',
            'description' => 'Create, update or delete a table.',
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
