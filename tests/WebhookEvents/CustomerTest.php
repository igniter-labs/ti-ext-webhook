<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\WebhookEvents;

use Igniter\User\Models\Customer;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Models\Outgoing;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Spatie\WebhookServer\CallWebhookJob;

it('runs webhook event when customer is created', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Customer created',
        'url' => 'http://webhook.tld',
        'events' => ['customer'],
        'is_active' => true,
    ]);

    Customer::factory()->create();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->toHaveKey('customer');

        return true;
    });

});

it('runs webhook event when customer is updated', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Customer updated',
        'url' => 'http://webhook.tld',
        'events' => ['customer'],
        'is_active' => true,
    ]);

    $customer = Customer::factory()->createQuietly();
    $customer->first_name = 'Updated Customer';
    $customer->save();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'updated')
            ->and($job->payload)->toHaveKey('customer');

        return true;
    });
});

it('runs webhook event when customer is deleted', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Customer deleted',
        'url' => 'http://webhook.tld',
        'events' => ['customer'],
        'is_active' => true,
    ]);

    $customer = Customer::factory()->createQuietly();
    $customer->delete();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) use ($customer): true {
        expect($job->payload)->toHaveKey('action', 'deleted')
            ->and($job->payload)->toHaveKey('customer', $customer->toArray());

        return true;
    });
});

it('runs menu webhook event with missing payload', function(): void {
    Queue::fake();
    Event::fake();

    $action = 'created';
    Outgoing::create([
        'name' => 'Customer '.$action,
        'url' => 'http://webhook.tld',
        'events' => ['customer'],
        'is_active' => true,
    ]);

    $payload = \IgniterLabs\Webhook\WebhookEvents\Customer::makePayloadFromEvent([], $action);
    resolve(WebhookManager::class)->runWebhookEvent(
        'customer', $action, $payload ?? [],
    );

    Event::assertDispatched('igniterlabs.webhook.beforeDispatch');
    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->not->toHaveKey('customer');

        return true;
    });
});
