<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\WebhookEvents;

use Igniter\Reservation\Models\DiningTable;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Models\Outgoing;
use IgniterLabs\Webhook\WebhookEvents\Table;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Spatie\WebhookServer\CallWebhookJob;

it('runs webhook event when table is created', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Table created',
        'url' => 'http://webhook.tld',
        'events' => ['table'],
        'is_active' => true,
    ]);

    $table = DiningTable::factory()->create();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) use ($table): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->toHaveKey('table', $table->toArray());

        return true;
    });

});

it('runs webhook event when table is updated', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Table updated',
        'url' => 'http://webhook.tld',
        'events' => ['table'],
        'is_active' => true,
    ]);

    $table = DiningTable::factory()->createQuietly();
    $table->name = 'Updated Table';
    $table->save();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'updated')
            ->and($job->payload)->toHaveKey('table');

        return true;
    });
});

it('runs webhook event when table is deleted', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Table deleted',
        'url' => 'http://webhook.tld',
        'events' => ['table'],
        'is_active' => true,
    ]);

    $table = DiningTable::factory()->createQuietly();
    $table->delete();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'deleted')
            ->and($job->payload)->toHaveKey('table');

        return true;
    });
});

it('runs menu webhook event with missing payload', function(): void {
    Queue::fake();
    Event::fake();

    $action = 'created';
    Outgoing::create([
        'name' => 'Table '.$action,
        'url' => 'http://webhook.tld',
        'events' => ['table'],
        'is_active' => true,
    ]);

    $payload = Table::makePayloadFromEvent([], $action);
    resolve(WebhookManager::class)->runWebhookEvent(
        'table', $action, $payload ?? [],
    );

    Event::assertDispatched('igniterlabs.webhook.beforeDispatch');
    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->not->toHaveKey('table');

        return true;
    });
});
