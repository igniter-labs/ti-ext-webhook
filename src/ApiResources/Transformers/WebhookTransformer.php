<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\ApiResources\Transformers;

use League\Fractal\TransformerAbstract;

class WebhookTransformer extends TransformerAbstract
{
    public function transform($resource)
    {
        return $resource->toArray();
    }
}
