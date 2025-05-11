<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Jobs;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\TransferStats;
use IgniterLabs\Webhook\Classes\EventPayload;
use IgniterLabs\Webhook\Exceptions\CouldNotCallWebhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CallWebhook implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $webhookUrl;

    public $httpVerb;

    public $postAsJson = true;

    public $tries;

    public $requestTimeout;

    public $headers = [];

    public bool $verifySsl;

    public $queue;

    public $payload = [];

    public $meta = [];

    public $tags = [];

    public $uuid = '';

    protected $response;

    protected $errorType;

    protected $errorMessage;

    protected ?TransferStats $transferStats = null;

    public function handle(): void
    {
        /** @var Client $client */
        $client = app(Client::class);

        $lastAttempt = $this->attempts() >= $this->tries;

        try {
            if (strtoupper((string) $this->httpVerb) === 'GET') {
                $body = ['query' => $this->payload];
            } else {
                $body = $this->postAsJson
                    ? ['body' => json_encode($this->payload)]
                    : ['form_params' => $this->payload];
            }

            $this->response = $client->request($this->httpVerb, $this->webhookUrl, array_merge([
                'timeout' => $this->requestTimeout,
                'verify' => $this->verifySsl,
                'headers' => $this->headers,
                'on_stats' => function(TransferStats $stats): void {
                    $this->transferStats = $stats;
                },
            ], $body));

            if (!Str::startsWith($this->response->getStatusCode(), 2)) {
                throw CouldNotCallWebhook::failed();
            }

            $this->dispatchEvent('igniterlabs.webhook.succeeded');

            return;
        } catch (Exception $exception) {
            if ($exception instanceof RequestException) {
                $this->response = $exception->getResponse();
                $this->errorType = $exception::class;
                $this->errorMessage = $exception->getMessage();
            }

            if ($exception instanceof ConnectException) {
                $this->errorType = $exception::class;
                $this->errorMessage = $exception->getMessage();
            }

            if (!$lastAttempt) {
                $this->release($this->waitInSecondsAfterAttempt($this->attempts()));
            }

            $this->dispatchEvent('igniterlabs.webhook.failed');
        }

        if ($lastAttempt) {
            $this->dispatchEvent('igniterlabs.webhook.lastAttempted');

            $this->delete();
        }
    }

    public function tags(): array
    {
        return $this->tags;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    protected function waitInSecondsAfterAttempt(int $attempt): int
    {
        if ($attempt > 4) {
            return 100000;
        }

        return 10 ** $attempt;
    }

    protected function dispatchEvent(string $eventClass)
    {
        event($eventClass, new EventPayload(
            $this->httpVerb,
            $this->webhookUrl,
            $this->payload,
            $this->headers,
            $this->meta,
            $this->tags,
            $this->attempts(),
            $this->response,
            $this->errorType,
            $this->errorMessage,
            $this->uuid,
            $this->transferStats
        ));
    }
}
