<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\ApiResources;

use Igniter\Api\Classes\ApiController;
use Igniter\Api\Http\Actions\RestController;
use IgniterLabs\Webhook\ApiResources\Repositories\OutgoingRepository;
use IgniterLabs\Webhook\ApiResources\Transformers\WebhookTransformer;
use IgniterLabs\Webhook\Http\Requests\OutgoingRequest;

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
            'index' => [
                'pageLimit' => 20,
            ],
            'show' => [],
            'store' => [],
            'update' => [],
            'destroy' => [],
        ],
        'request' => OutgoingRequest::class,
        'repository' => OutgoingRepository::class,
        'transformer' => WebhookTransformer::class,
    ];

    protected string|array $requiredAbilities = ['webhooks:*'];
}
