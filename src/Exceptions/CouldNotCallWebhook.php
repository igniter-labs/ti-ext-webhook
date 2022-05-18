<?php

namespace IgniterLabs\Webhook\Exceptions;

use Exception;

class CouldNotCallWebhook extends Exception
{
    public static function failed(): self
    {
        return new static('Webhook call failed');
    }

    public static function urlNotSet(): self
    {
        return new static('Could not call the webhook because the url has not been set.');
    }

    public static function secretNotSet(): self
    {
        return new static('Could not call the webhook because no secret has been set.');
    }
}
