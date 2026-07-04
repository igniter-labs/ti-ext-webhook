<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\Jobs;

use GuzzleHttp\Client;
use IgniterLabs\Webhook\Jobs\SafeCallWebhookJob;
use ReflectionMethod;
use ReflectionProperty;

it('returns guzzle client with redirects disabled', function(): void {
    $job = new SafeCallWebhookJob;

    $getClient = new ReflectionMethod(SafeCallWebhookJob::class, 'getClient');
    $client = $getClient->invoke($job);

    expect($client)->toBeInstanceOf(Client::class);

    $config = new ReflectionProperty(Client::class, 'config');

    expect($config->getValue($client)['allow_redirects'])->toBeFalse();
});

it('does not resolve webhook client from the container', function(): void {
    $containerClient = new Client(['allow_redirects' => true]);
    app()->instance(Client::class, $containerClient);

    $job = new SafeCallWebhookJob;

    $getClient = new ReflectionMethod(SafeCallWebhookJob::class, 'getClient');
    $client = $getClient->invoke($job);

    expect($client)->not->toBe($containerClient);
});
