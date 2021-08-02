<?php

namespace IgniterLabs\Webhook\WebhookActions;

use IgniterLabs\Webhook\Classes\BaseAction;
use IgniterLabs\Webhook\Traits\ProcessWebhookActions;

class Order extends BaseAction
{
    use ProcessWebhookActions;

    protected $modelClass = \Admin\Models\Orders_model::class;

    protected $requestClass = \Igniter\Api\ApiResources\Requests\OrderRequest::class;

    /**
     * {@inheritdoc}
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

    public function processAssignAction()
    {
    }
}
