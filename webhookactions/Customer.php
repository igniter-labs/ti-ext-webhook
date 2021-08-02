<?php

namespace IgniterLabs\Webhook\WebhookActions;

use Igniter\Flame\Exception\ApplicationException;
use IgniterLabs\Webhook\Classes\BaseAction;
use IgniterLabs\Webhook\Traits\ProcessWebhookActions;

class Customer extends BaseAction
{
    use ProcessWebhookActions;

    protected $modelClass = \Admin\Models\Customers_model::class;

    protected $requestClass = \Admin\Requests\Customer::class;

    /**
     * {@inheritdoc}
     */
    public function actionDetails()
    {
        return [
            'name' => 'Customers',
            'description' => 'Create, update or delete a customer.',
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

    protected function getRecordId($webhookCall)
    {
        if (!strlen($recordId = array_get($webhookCall->payload, 'customer_id')))
            throw new ApplicationException('Please provide a customer_id parameter');

        return $recordId;
    }
}
