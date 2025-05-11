<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\ApiResources;

use Igniter\Api\Classes\ApiController;
use Igniter\Api\Http\Actions\RestController;
use IgniterLabs\Webhook\ApiResources\Transformers\WebhookTransformer;
use IgniterLabs\Webhook\Http\Requests\OutgoingRequest;
use IgniterLabs\Webhook\Models\Outgoing;

/**
 * Webhooks API Controller
 */
class Webhooks extends ApiController
{
    public array $implement = [
        RestController::class,
    ];

    public $restConfig = [
        'actions' => [
            'store' => [],
            'update' => [],
            'destroy' => [],
        ],
        'request' => OutgoingRequest::class,
        'repository' => Outgoing::class,
        'transformer' => WebhookTransformer::class,
    ];

    protected string|array $requiredAbilities = ['webhooks:*'];
}
