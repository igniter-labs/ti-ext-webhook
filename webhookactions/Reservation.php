<?php

namespace IgniterLabs\Webhook\WebhookActions;

use IgniterLabs\Webhook\Classes\BaseAction;
use IgniterLabs\Webhook\Traits\ProcessWebhookActions;

class Reservation extends BaseAction
{
    use ProcessWebhookActions;

    protected $modelClass = \Admin\Models\Reservations_model::class;

    protected $requestClass = \Igniter\Api\ApiResources\Requests\ReservationRequest::class;

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

    public function processAssignAction()
    {
    }
}
