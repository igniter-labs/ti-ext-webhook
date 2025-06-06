<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\Models;

use Igniter\Flame\Exception\SystemException;
use IgniterLabs\Webhook\Models\Outgoing;

it('can list webhooks for an event', function(): void {
    Outgoing::factory()->create([
        'name' => 'Test Webhook',
        'url' => 'http://webhook.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    Outgoing::factory()->create([
        'name' => 'Inactive Webhook',
        'url' => 'http://webhook.tld',
        'events' => ['category'],
        'is_active' => false,
    ]);

    Outgoing::factory()->create([
        'name' => 'Different Event Webhook',
        'url' => 'http://webhook.tld',
        'events' => ['order'],
        'is_active' => true,
    ]);

    $webhooks = Outgoing::listWebhooksForEvent('category');

    expect($webhooks)->toHaveCount(1)
        ->and($webhooks->first()->name)->toBe('Test Webhook');
});

it('generates a secret key on creation if not provided', function(): void {
    Outgoing::flushEventListeners();
    $outgoing = Outgoing::factory()->create([
        'name' => 'Test Webhook',
        'url' => 'http://webhook.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    expect($outgoing->config_data)->toHaveKey('secret_key')
        ->and($outgoing->config_data['secret_key'])->toBeString()
        ->and(strlen((string) $outgoing->config_data['secret_key']))->toBe(16);
});

it('throws an exception when dispatching without a URL', function(): void {
    $outgoing = new Outgoing;
    $outgoing->url = '';

    expect(fn() => $outgoing->dispatchWebhook('created', 'category'))
        ->toThrow(SystemException::class, 'Missing a webhook payload URL.');
});
