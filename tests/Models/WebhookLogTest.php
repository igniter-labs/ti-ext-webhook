<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\Models;

use GuzzleHttp\Psr7\Response;
use IgniterLabs\Webhook\Models\Outgoing;
use IgniterLabs\Webhook\Models\WebhookLog;
use Mockery;
use Spatie\WebhookServer\Events\FinalWebhookCallFailedEvent;
use Spatie\WebhookServer\Events\WebhookCallSucceededEvent;

beforeEach(function(): void {
    $this->webhookLog = new WebhookLog;
});

it('can create a log from a successful webhook call event', function(): void {
    $webhook = Outgoing::factory()->create([
        'name' => 'Test Webhook',
        'url' => 'http://webhook.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    $response = Mockery::mock(Response::class);
    $response->shouldReceive('getBody->getContents')->andReturn('{"status":"success"}');

    $event = new WebhookCallSucceededEvent(
        'POST',
        'http://webhook.tld',
        ['test' => 'data'],
        [],
        [
            'webhook_id' => $webhook->getKey(),
            'webhook_type' => $webhook->getMorphClass(),
            'name' => 'Test Webhook',
            'event_code' => 'category',
        ],
        [],
        3,
        $response,
        null,
        null,
        fake()->uuid(),
        null,
    );

    $log = WebhookLog::createLog($event);

    expect($log)->toBeInstanceOf(WebhookLog::class)
        ->and($log->webhook_id)->toBe($webhook->getKey())
        ->and($log->webhook_type)->toBe($webhook->getMorphClass())
        ->and($log->name)->toBe('Test Webhook')
        ->and($log->event_code)->toBe('category')
        ->and($log->payload)->toBe(['test' => 'data'])
        ->and($log->is_success)->toBeTrue()
        ->and($log->message)->toBe('Payload delivered successfully')
        ->and($log->response)->toBe('{"status":"success"}');
});

it('can create a log from a failed webhook call event', function(): void {
    $webhook = Outgoing::factory()->create([
        'name' => 'Test Webhook',
        'url' => 'http://webhook.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    $event = new FinalWebhookCallFailedEvent(
        'POST',
        'http://webhook.tld',
        ['test' => 'data'],
        [],
        [
            'webhook_id' => $webhook->getKey(),
            'webhook_type' => $webhook->getMorphClass(),
            'name' => 'Test Webhook',
            'event_code' => 'category',
        ],
        [],
        3,
        null,
        'ConnectionError',
        'Failed to connect',
        fake()->uuid(),
        null,
    );

    $log = WebhookLog::createLog($event);

    expect($log)->toBeInstanceOf(WebhookLog::class)
        ->and($log->webhook_id)->toBe($webhook->getKey())
        ->and($log->webhook_type)->toBe($webhook->getMorphClass())
        ->and($log->name)->toBe('Test Webhook')
        ->and($log->event_code)->toBe('category')
        ->and($log->payload)->toBe(['test' => 'data'])
        ->and($log->is_success)->toBeFalse()
        ->and($log->message)->toBe('ConnectionError:Failed to connect');
});

it('can filter logs by webhook', function(): void {
    $webhook1 = Outgoing::factory()->create([
        'name' => 'Test Webhook 1',
        'url' => 'http://webhook1.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    $webhook2 = Outgoing::factory()->create([
        'name' => 'Test Webhook 2',
        'url' => 'http://webhook2.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    WebhookLog::create([
        'webhook_id' => $webhook1->getKey(),
        'webhook_type' => $webhook1->getMorphClass(),
        'name' => 'Test Webhook 1',
        'event_code' => 'category',
        'payload' => ['test' => 'data1'],
        'is_success' => true,
        'message' => 'Success',
    ]);

    WebhookLog::create([
        'webhook_id' => $webhook2->getKey(),
        'webhook_type' => $webhook2->getMorphClass(),
        'name' => 'Test Webhook 2',
        'event_code' => 'category',
        'payload' => ['test' => 'data2'],
        'is_success' => true,
        'message' => 'Success',
    ]);

    $logs = WebhookLog::applyWebhook($webhook1)->get();

    expect($logs)->toHaveCount(1)
        ->and($logs->first()->name)->toBe('Test Webhook 1');
});

it('has a status name attribute', function(): void {
    $successLog = new WebhookLog(['is_success' => true]);
    $failedLog = new WebhookLog(['is_success' => false]);

    expect($successLog->status_name)->toBe(lang('igniterlabs.webhook::default.text_success'))
        ->and($failedLog->status_name)->toBe(lang('igniterlabs.webhook::default.text_failed'));
});

it('has a created since attribute', function(): void {
    $log = new WebhookLog(['created_at' => now()]);

    expect($log->created_since)->toContain('Today at');
});

it('defines prunable records', function(): void {
    $query = (new WebhookLog)->prunable();

    expect($query->toSql())->toContain('where')
        ->and($query->toSql())->toContain('created_at');
});
