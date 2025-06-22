<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\WebhookEvents;

use Igniter\Admin\Models\Status;
use Igniter\Cart\Models\Order;
use Igniter\User\Models\User;
use Igniter\User\Models\UserGroup;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Models\Outgoing;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Spatie\WebhookServer\CallWebhookJob;

it('runs webhook event when order is created', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Order created',
        'url' => 'http://webhook.tld',
        'events' => ['order'],
        'is_active' => true,
    ]);

    $order = Order::factory()->create();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) use ($order): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->toHaveKey('order', $order->toArray())
            ->toHaveKey('order_menus', $order->getOrderMenusWithOptions()->toArray())
            ->toHaveKey('order_totals', $order->getOrderTotals()->toArray());

        return true;
    });
});

it('runs webhook event when order is updated', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Order updated',
        'url' => 'http://webhook.tld',
        'events' => ['order'],
        'is_active' => true,
    ]);

    $order = Order::factory()->createQuietly();
    $order->first_name = 'Updated Name';
    $order->save();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'updated')
            ->toHaveKey('order')
            ->toHaveKey('order_menus')
            ->toHaveKey('order_totals');

        return true;
    });
});

it('runs webhook event when customer places an order', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Order placed',
        'url' => 'http://webhook.tld',
        'events' => ['order'],
        'is_active' => true,
    ]);

    $order = Order::factory()->createQuietly();
    Order::withoutEvents(fn() => $order->markAsPaymentProcessed());

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) use ($order): true {
        expect($job->payload)->toHaveKey('action', 'placed')
            ->toHaveKey('order', $order->toArray())
            ->toHaveKey('order_menus', $order->getOrderMenusWithOptions()->toArray())
            ->toHaveKey('order_totals', $order->getOrderTotals()->toArray());

        return true;
    });
});

it('runs webhook event when order status is updated', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Order status added',
        'url' => 'http://webhook.tld',
        'events' => ['order'],
        'is_active' => true,
    ]);

    $order = Order::factory()->createQuietly();
    $status = Status::factory()->create();
    $order->addStatusHistory($status);

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload['action'])->toBeIn(['updated', 'status_added'])
            ->and($job->payload)->toHaveKey('order')
            ->toHaveKey('order_menus')
            ->toHaveKey('order_totals')
            ->when($job->payload['action'] == 'status_added', function() use ($job): void {
                expect($job->payload)->toHaveKey('status_history');
            });

        return true;
    });
});

it('runs webhook event when order is assigned to staff member', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Order assigned',
        'url' => 'http://webhook.tld',
        'events' => ['order'],
        'is_active' => true,
    ]);

    $order = Order::factory()->createQuietly();
    $staffMember = User::factory()->has(UserGroup::factory(), 'groups')->create();
    Order::withoutEvents(fn() => $order->assignToGroup($staffMember->groups->first(), $staffMember));

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'assigned')
            ->toHaveKey('order')
            ->toHaveKey('order_menus')
            ->toHaveKey('order_totals');

        return true;
    });
});

it('runs webhook event when order is deleted', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Order deleted',
        'url' => 'http://webhook.tld',
        'events' => ['order'],
        'is_active' => true,
    ]);

    $order = Order::factory()->createQuietly();
    $order->delete();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'deleted')
            ->and($job->payload)->toHaveKey('order');

        return true;
    });
});

it('runs order webhook event with missing payload', function(): void {
    Queue::fake();
    Event::fake();

    $action = 'created';
    Outgoing::factory()->create([
        'name' => 'Order '.$action,
        'url' => 'http://webhook.tld',
        'events' => ['order'],
        'is_active' => true,
    ]);

    $payload = \IgniterLabs\Webhook\WebhookEvents\Order::makePayloadFromEvent([], $action);
    resolve(WebhookManager::class)->runWebhookEvent(
        'order', $action, $payload ?? [],
    );

    Event::assertDispatched('igniterlabs.webhook.beforeDispatch');
    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->not->toHaveKey('order');

        return true;
    });
});
