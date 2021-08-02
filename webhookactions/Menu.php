<?php

namespace IgniterLabs\Webhook\WebhookActions;

use Igniter\Flame\Exception\ApplicationException;
use IgniterLabs\Webhook\Classes\BaseAction;
use IgniterLabs\Webhook\Traits\ProcessWebhookActions;

class Menu extends BaseAction
{
    use ProcessWebhookActions;

    protected $modelClass = \Admin\Models\Menus_model::class;

    protected $requestClass = \Admin\Requests\Menu::class;

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

    protected function getRecordId($webhookCall)
    {
        if (!strlen($recordId = array_get($webhookCall->payload, 'customer_id')))
            throw new ApplicationException('Please provide a customer_id parameter');

        return $recordId;
    }
}
