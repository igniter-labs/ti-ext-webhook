<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\Classes;

use Igniter\System\Classes\ExtensionManager;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Models\Settings;
use IgniterLabs\Webhook\WebhookEvents\Category;
use Illuminate\Support\Facades\Schema;
use Mockery;

beforeEach(function(): void {
    $this->manager = new WebhookManager;
});

it('does not boot when already booted', function() {
    $this->manager->boot();

    // Call boot again to check if it does not change the booted state
    $this->manager->boot();

    expect($this->manager->isBooted())->toBeTrue();
});

it('does not boot when not configured', function() {
    Schema::shouldReceive('hasTable')
        ->with('igniterlabs_webhook_outgoing')
        ->andReturnFalse();

    $this->manager->boot();

    expect($this->manager->isBooted())->toBeFalse();
});

it('applies webhook config values', function(): void {
    Settings::set([
        'verify_ssl' => true,
        'timeout_in_seconds' => 30,
        'tries' => 5,
        'server_signature_header' => 'X-Webhook-Signature',
        'headers' => [
            ['key' => 'Test-Header', 'value' => 'value'],
        ],
    ]);

    $this->manager->applyWebhookConfigValues();

    expect(config('webhook-server'))
        ->toHaveKey('verify_ssl', true)
        ->toHaveKey('timeout_in_seconds', 30)
        ->toHaveKey('tries', 5)
        ->toHaveKey('signature_header_name', 'X-Webhook-Signature')
        ->toHaveKey('headers', [
            'Content-Type' => 'application/json',
            'Test-Header' => 'value',
        ]);
});

it('lists event objects', function(): void {
    // Mock the listEvents method
    $mockManager = Mockery::mock($this->manager)->makePartial();
    $mockManager->shouldReceive('listEvents')
        ->andReturn(['category' => Category::class]);

    $eventObjects = $mockManager->listEventObjects();

    expect($eventObjects)->toHaveKey('category')
        ->and($eventObjects['category'])->toBeInstanceOf(Category::class);
});

it('lists events', function(): void {
    $extensionManager = Mockery::mock(ExtensionManager::class);
    $extensionManager->shouldReceive('getRegistrationMethodValues')
        ->with('registerWebhookEvents')
        ->andReturn([
            ['category' => Category::class],
        ]);

    app()->instance(ExtensionManager::class, $extensionManager);

    $events = $this->manager->listEvents();

    expect($events)->toHaveKey('category')
        ->and($events['category'])->toBe(Category::class);
});

it('registers a webhook event', function(): void {
    $this->manager->registerWebhookEvent(['test' => 'TestClass']);

    $events = $this->manager->listEvents();

    expect($events)->toHaveKey('test')
        ->and($events['test'])->toBe('TestClass');
});

it('registers a callback', function(): void {
    $called = false;

    $this->manager->registerCallback(function($manager) use (&$called) {
        $called = true;
        $manager->registerWebhookEvent(['callback' => 'CallbackClass']);
    });

    $events = $this->manager->listEvents();

    expect($called)->toBeTrue();
    expect($events)->toHaveKey('callback');
    expect($events['callback'])->toBe('CallbackClass');
});
