<?php

namespace IgniterLabs\Webhook\ApiResources;

use Igniter\Api\Classes\ApiController;

/**
 * Webhooks API Controller
 */
class Webhooks extends ApiController
{
    public array $implement = [
        \Igniter\Api\Http\Actions\RestController::class,
    ];

    public $restConfig = [
        'actions' => [
            'store' => [],
            'update' => [],
            'destroy' => [],
        ],
        'request' => \IgniterLabs\Webhook\Http\Requests\OutgoingRequest::class,
        'repository' => \IgniterLabs\Webhook\Models\Outgoing::class,
        'transformer' => \IgniterLabs\Webhook\ApiResources\Transformers\WebhookTransformer::class,
    ];

    protected string|array $requiredAbilities = ['webhooks:*'];
}
