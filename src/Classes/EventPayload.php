<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Classes;

use GuzzleHttp\Psr7\Response;

class EventPayload
{
    /**
     * @var string
     */
    public $httpVerb;

    /**
     * @var string
     */
    public $webhookUrl;

    /**
     * @var mixed[]
     */
    public $payload;

    /**
     * @var mixed[]
     */
    public $headers;

    /**
     * @var mixed[]
     */
    public $meta;

    /**
     * @var mixed[]
     */
    public $tags;

    /**
     * @var int
     */
    public $attempt;

    /**
     * @var null|Response
     */
    public $response;

    /**
     * @var string|null
     */
    public $errorType;

    /**
     * @var string|null
     */
    public $errorMessage;

    /**
     * @var string
     */
    public $uuid;

    public function __construct(
        string $httpVerb,
        string $webhookUrl,
        array $payload,
        array $headers,
        array $meta,
        array $tags,
        int $attempt,
        ?Response $response,
        ?string $errorType,
        ?string $errorMessage,
        string $uuid
    ) {
        $this->httpVerb = $httpVerb;
        $this->webhookUrl = $webhookUrl;
        $this->payload = $payload;
        $this->headers = $headers;
        $this->meta = $meta;
        $this->tags = $tags;
        $this->attempt = $attempt;
        $this->response = $response;
        $this->errorType = $errorType;
        $this->errorMessage = $errorMessage;
        $this->uuid = $uuid;
    }
}
