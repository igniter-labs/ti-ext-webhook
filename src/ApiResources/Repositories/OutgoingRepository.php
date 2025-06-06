<?php

namespace IgniterLabs\Webhook\ApiResources\Repositories;

use Igniter\Api\Classes\AbstractRepository;
use IgniterLabs\Webhook\Models\Outgoing;

class OutgoingRepository extends AbstractRepository
{
    protected ?string $modelClass = Outgoing::class;
}
