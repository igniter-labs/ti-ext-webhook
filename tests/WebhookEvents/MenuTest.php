<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\WebhookEvents;

use Igniter\Cart\Models\Menu;
use Igniter\Cart\Models\Stock;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Models\Outgoing;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Spatie\WebhookServer\CallWebhookJob;

it('runs webhook event when menu is created', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Menu created',
        'url' => 'http://webhook.tld',
        'events' => ['menu'],
        'is_active' => true,
    ]);

    Menu::factory()->create();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->toHaveKey('menu');

        return true;
    });
});

it('runs webhook event when menu is updated', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Menu updated',
        'url' => 'http://webhook.tld',
        'events' => ['menu'],
        'is_active' => true,
    ]);

    $menu = Menu::factory()->createQuietly();
    $menu->menu_name = 'Updated Menu';
    $menu->save();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'updated')
            ->and($job->payload)->toHaveKey('menu');

        return true;
    });
});

it('runs webhook event when menu is deleted', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Menu deleted',
        'url' => 'http://webhook.tld',
        'events' => ['menu'],
        'is_active' => true,
    ]);

    $menu = Menu::factory()->createQuietly();
    $menu->delete();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'deleted')
            ->and($job->payload)->toHaveKey('menu');

        return true;
    });
});

it('runs webhook event when menu stock is updated', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Menu stock updated',
        'url' => 'http://webhook.tld',
        'events' => ['menu'],
        'is_active' => true,
    ]);

    $menu = Menu::factory()->hasStocks(1, ['is_tracked' => true])->createQuietly();
    $menu->stocks->first()->updateStock(10, Stock::STATE_RESTOCK);

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'stock_updated')
            ->and($job->payload)->toHaveKey('menu')
            ->and($job->payload)->toHaveKey('stock')
            ->and($job->payload)->toHaveKey('stock_history');

        return true;
    });
});

it('runs menu webhook event with missing payload', function(): void {
    Queue::fake();
    Event::fake();

    $action = 'created';
    Outgoing::factory()->create([
        'name' => 'Menu '.$action,
        'url' => 'http://webhook.tld',
        'events' => ['menu'],
        'is_active' => true,
    ]);

    $payload = \IgniterLabs\Webhook\WebhookEvents\Menu::makePayloadFromEvent([], $action);
    resolve(WebhookManager::class)->runWebhookEvent(
        'menu', $action, $payload ?? [],
    );

    Event::assertDispatched('igniterlabs.webhook.beforeDispatch');
    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->not->toHaveKey('menu');

        return true;
    });
});
