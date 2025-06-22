<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\WebhookEvents;

use Igniter\Cart\Models\Category;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Models\Outgoing;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Spatie\WebhookServer\CallWebhookJob;

it('runs webhook event when category is created', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Category created',
        'url' => $url = 'http://webhook.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    $category = Category::factory()->create();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) use ($category): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->toHaveKey('category', $category->toArray());

        return true;
    });
});

it('runs webhook event when category is updated', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Category updated',
        'url' => $url = 'http://webhook.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    $category = Category::factory()->createQuietly();
    $category->name = 'Updated Category';
    $category->save();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) use ($category): true {
        expect($job->payload)->toHaveKey('action', 'updated')
            ->and($job->payload)->toHaveKey('category', $category->toArray());

        return true;
    });
});

it('runs webhook event when category is deleted', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Category deleted',
        'url' => $url = 'http://webhook.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    $category = Category::factory()->createQuietly();
    $category->delete();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'deleted');

        return true;
    });
});

it('runs category webhook event with missing payload', function(): void {
    Queue::fake();
    Event::fake();

    $action = 'created';
    Outgoing::factory()->create([
        'name' => 'Category '.$action,
        'url' => 'http://webhook.tld',
        'events' => ['category'],
        'is_active' => true,
    ]);

    $payload = \IgniterLabs\Webhook\WebhookEvents\Category::makePayloadFromEvent([], $action);
    resolve(WebhookManager::class)->runWebhookEvent(
        'category', $action, $payload ?? [],
    );

    Event::assertDispatched('igniterlabs.webhook.beforeDispatch');
    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->not->toHaveKey('category');

        return true;
    });
});
