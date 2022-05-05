<?php

namespace IgniterLabs\Webhook\Tests;

use IgniterLabs\Webhook\Classes\WebhookCall;
use IgniterLabs\Webhook\Exceptions\CouldNotCallWebhook;
use IgniterLabs\Webhook\Jobs\CallWebhook;
use Illuminate\Support\Facades\Queue;

uses(\Tests\ExtensionTestCase::class);

beforeEach(function () {
    Queue::fake();
});

it('can_dispatch_a_job_that_calls_a_webhook', function () {
    $url = 'https://localhost';

    WebhookCall::create()->url($url)->useSecret('123')->dispatch();

    Queue::assertPushed(CallWebhook::class, function (CallWebhook $job) use ($url) {
        $config = config('webhook-server');

        $this->assertEquals($config['queue'], $job->queue);
        $this->assertEquals($url, $job->webhookUrl);
        $this->assertEquals($config['http_verb'], $job->httpVerb);
        $this->assertEquals($config['tries'], $job->tries);
        $this->assertEquals($config['timeout_in_seconds'], $job->requestTimeout);
        $this->assertEquals($config['backoff_strategy'], $job->backoffStrategyClass);
        $this->assertContains($config['signature_header_name'], array_keys($job->headers));
        $this->assertEquals($config['verify_ssl'], $job->verifySsl);
        $this->assertEquals($config['tags'], $job->tags);

        return true;
    });
});

test('can_keep_default_config_headers_and_set_new_ones', function () {
    $url = 'https://localhost';

    WebhookCall::create()->url($url)
        ->withHeaders(['User-Agent' => 'IgniterLabs/Webhook-Server'])
        ->useSecret('123')
        ->dispatch();

    Queue::assertPushed(CallWebhook::class, function (CallWebhook $job) use ($url) {
        $config = config('webhook-server');

        $this->assertArrayHasKey('Content-Type', $job->headers);
        $this->assertArrayHasKey('User-Agent', $job->headers);

        return true;
    });
});

it('can_override_default_config_headers', function () {
    $url = 'https://localhost';

    WebhookCall::create()->url($url)
        ->withHeaders(['Content-Type' => 'text/plain'])
        ->useSecret('123')
        ->dispatch();

    Queue::assertPushed(CallWebhook::class, function (CallWebhook $job) use ($url) {
        $config = config('webhook-server');

        $this->assertArrayHasKey('Content-Type', $job->headers);
        $this->assertEquals('text/plain', $job->headers['Content-Type']);

        return true;
    });
});

it('can_override_default_queue_connection', function () {
    $url = 'https://localhost';

    WebhookCall::create()->url($url)
        ->onConnection('foo')
        ->useSecret('123')
        ->dispatch();

    Queue::assertPushed(CallWebhook::class, function (CallWebhook $job) use ($url) {
        $this->assertEquals('foo', $job->connection);

        return true;
    });
});

it('will_throw_an_exception_when_calling_a_webhook_without_proving_an_url', function () {
    $this->expectException(CouldNotCallWebhook::class);

    WebhookCall::create()->dispatch();
});

it('will_throw_an_exception_when_no_secret_has_been_set', function () {
    $this->expectException(CouldNotCallWebhook::class);

    WebhookCall::create()->url('https://localhost')->dispatch();
});

it('will_not_throw_an_exception_if_there_is_not_secret_and_the_request_should_not_be_signed', function () {
    WebhookCall::create()->doNotSign()->url('https://localhost')->dispatch();

    $this->assertTrue(true);
});

it('can_get_the_uuid_property', function () {
    $webhookCall = WebhookCall::create()->uuid('my-unique-identifier');

    $this->assertIsString($webhookCall->getUuid());
    $this->assertSame('my-unique-identifier', $webhookCall->getUuid());
});
