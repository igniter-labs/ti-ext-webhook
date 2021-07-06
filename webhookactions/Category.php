<?php

namespace IgniterLabs\Webhook\WebhookActions;

use IgniterLabs\Webhook\Classes\BaseAction;

class Category extends BaseAction
{
    /**
     * {@inheritdoc}
     */
    public function actionDetails()
    {
        return [
            'name' => 'Category',
            'description' => 'Create, update or delete a category.',
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
