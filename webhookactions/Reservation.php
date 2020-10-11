<?php

namespace IgniterLabs\Webhook\WebhookActions;

use IgniterLabs\Webhook\Classes\BaseAction;

class Reservation extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    public function actionDetails()
    {
        return [
            'name' => 'Reservations',
            'description' => 'Create, status update, assign or delete an reservation.',
        ];
    }

    public function registerEntryPoints()
    {
        return [
            'create' => 'processCreateAction',
            'update' => 'processUpdateAction',
            'assign' => 'processAssignAction',
            'delete' => 'processDeleteAction',
        ];
    }
}
