<?php

namespace IgniterLabs\Webhook\WebhookActions;

use Igniter\Flame\Exception\ApplicationException;
use IgniterLabs\Webhook\Classes\BaseAction;
use IgniterLabs\Webhook\Traits\ProcessWebhookActions;

class Category extends BaseAction
{
    use ProcessWebhookActions;

    protected $modelClass = \Admin\Models\Categories_model::class;

    protected $requestClass = \Admin\Requests\Category::class;

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

    protected function getRecordId($webhookCall)
    {
        if (!strlen($recordId = array_get($webhookCall->payload, 'category_id')))
            throw new ApplicationException('Please provide a category_id parameter');

        return $recordId;
    }
}
