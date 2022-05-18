<?php

namespace IgniterLabs\Webhook\Classes;

use IgniterLabs\Webhook\Exceptions\CouldNotCallWebhook;
use IgniterLabs\Webhook\Jobs\CallWebhook;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Str;

class WebhookCall
{
    protected $callWebhookJob;

    protected $uuid = '';

    protected $secret;

    protected $headers = [];

    private $payload = [];

    private $signWebhook = true;

    public static function create(): self
    {
        $config = config('webhook-server');

        return (new static())
            ->uuid(Str::uuid())
            ->onQueue($config['queue'])
            ->onConnection($config['connection'] ?? null)
            ->useHttpVerb($config['http_verb'])
            ->maximumTries($config['tries'])
            ->timeoutInSeconds($config['timeout_in_seconds'])
            ->withHeaders($config['headers'])
            ->withTags($config['tags'])
            ->verifySsl($config['verify_ssl']);
    }

    public function __construct()
    {
        $this->callWebhookJob = app(CallWebhook::class);
    }

    public function url(string $url): self
    {
        $this->callWebhookJob->webhookUrl = $url;

        return $this;
    }

    public function payload(array $payload): self
    {
        $this->payload = $payload;

        $this->callWebhookJob->payload = $payload;

        return $this;
    }

    public function uuid(string $uuid): self
    {
        $this->uuid = $uuid;

        $this->callWebhookJob->uuid = $uuid;

        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function onQueue(?string $queue): self
    {
        $this->callWebhookJob->queue = $queue;

        return $this;
    }

    public function onConnection(?string $connection): self
    {
        $this->callWebhookJob->connection = $connection;

        return $this;
    }

    public function useSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    public function useHttpVerb(string $verb): self
    {
        $this->callWebhookJob->httpVerb = $verb;

        return $this;
    }

    public function postAsJson(bool $postAsJson): self
    {
        $this->callWebhookJob->postAsJson = $postAsJson;

        return $this;
    }

    public function maximumTries(int $tries): self
    {
        $this->callWebhookJob->tries = $tries;

        return $this;
    }

    public function timeoutInSeconds(int $timeoutInSeconds): self
    {
        $this->callWebhookJob->requestTimeout = $timeoutInSeconds;

        return $this;
    }

    public function doNotSign(): self
    {
        $this->signWebhook = false;

        return $this;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function verifySsl(bool $verifySsl = true): self
    {
        $this->callWebhookJob->verifySsl = $verifySsl;

        return $this;
    }

    public function doNotVerifySsl(): self
    {
        $this->verifySsl(false);

        return $this;
    }

    public function meta(array $meta): self
    {
        $this->callWebhookJob->meta = $meta;

        return $this;
    }

    public function withTags(array $tags): self
    {
        $this->callWebhookJob->tags = $tags;

        return $this;
    }

    public function dispatch(): PendingDispatch
    {
        $this->prepareForDispatch();

        return dispatch($this->callWebhookJob);
    }

    public function dispatchSync(): void
    {
        $this->prepareForDispatch();

        dispatch_sync($this->callWebhookJob);
    }

    protected function prepareForDispatch(): void
    {
        if (!$this->callWebhookJob->webhookUrl) {
            throw CouldNotCallWebhook::urlNotSet();
        }

        if ($this->signWebhook && empty($this->secret)) {
            throw CouldNotCallWebhook::secretNotSet();
        }

        $this->callWebhookJob->headers = $this->getAllHeaders();
    }

    protected function getAllHeaders(): array
    {
        $headers = $this->headers;

        if (!$this->signWebhook) {
            return $headers;
        }

        $signature = $this->calculateSignature($this->payload, $this->secret);

        $headers[$this->signatureHeaderName()] = $signature;

        return $headers;
    }

    protected function calculateSignature(array $payload, string $secret): string
    {
        $payloadJson = json_encode($payload);

        return hash_hmac('sha256', $payloadJson, $secret);
    }

    protected function signatureHeaderName(): string
    {
        return config('webhook-server.signature_header_name');
    }
}
