<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\WebhookEvents;

use Igniter\Admin\Models\Status;
use Igniter\Reservation\Models\Reservation;
use Igniter\User\Models\User;
use Igniter\User\Models\UserGroup;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Models\Outgoing;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Spatie\WebhookServer\CallWebhookJob;

it('runs webhook event when reservation is created', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Reservation created',
        'url' => 'http://webhook.tld',
        'events' => ['reservation'],
        'is_active' => true,
    ]);

    $reservation = Reservation::factory()->create();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) use ($reservation): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->toHaveKey('reservation', $reservation->toArray());

        return true;
    });

});

it('runs webhook event when reservation is updated', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Reservation updated',
        'url' => 'http://webhook.tld',
        'events' => ['reservation'],
        'is_active' => true,
    ]);

    $reservation = Reservation::factory()->createQuietly();
    $reservation->first_name = 'Updated Name';
    $reservation->save();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) use ($reservation): true {
        expect($job->payload)->toHaveKey('action', 'updated')
            ->and($job->payload)->toHaveKey('reservation', $reservation->toArray());

        return true;
    });
});

it('runs webhook event when reservation status is updated', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Reservation status added',
        'url' => 'http://webhook.tld',
        'events' => ['reservation'],
        'is_active' => true,
    ]);

    $reservation = Reservation::factory()->createQuietly();
    $status = Status::factory()->create();
    $reservation->addStatusHistory($status);

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload['action'])->toBeIn(['updated', 'status_added'])
            ->and($job->payload)->toHaveKey('reservation')
            ->when($job->payload['action'] == 'status_added', function() use ($job): void {
                expect($job->payload)->toHaveKey('status_history');
            });

        return true;
    });
});

it('runs webhook event when reservation is assigned to staff member', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Reservation assigned',
        'url' => 'http://webhook.tld',
        'events' => ['reservation'],
        'is_active' => true,
    ]);

    $staffMember = User::factory()->has(UserGroup::factory(), 'groups')->create();
    $reservation = Reservation::factory()->createQuietly();
    Reservation::withoutEvents(fn() => $reservation->assignToGroup($staffMember->groups->first(), $staffMember));

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) use ($reservation): true {
        expect($job->payload)->toHaveKey('action', 'assigned')
            ->toHaveKey('reservation', $reservation->toArray());

        return true;
    });
});

it('runs webhook event when reservation is deleted', function(): void {
    Queue::fake();

    Outgoing::factory()->create([
        'name' => 'Reservation deleted',
        'url' => 'http://webhook.tld',
        'events' => ['reservation'],
        'is_active' => true,
    ]);

    $reservation = Reservation::factory()->createQuietly();
    $reservation->delete();

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) use ($reservation): true {
        expect($job->payload)->toHaveKey('action', 'deleted')
            ->and($job->payload)->toHaveKey('reservation', $reservation->toArray());

        return true;
    });
});

it('runs reservation webhook event with missing payload', function(): void {
    Queue::fake();
    Event::fake();

    $action = 'created';
    Outgoing::create([
        'name' => 'Reservation '.$action,
        'url' => 'http://webhook.tld',
        'events' => ['reservation'],
        'is_active' => true,
    ]);

    $payload = \IgniterLabs\Webhook\WebhookEvents\Reservation::makePayloadFromEvent([], $action);
    resolve(WebhookManager::class)->runWebhookEvent(
        'reservation', $action, $payload ?? [],
    );

    Event::assertDispatched('igniterlabs.webhook.beforeDispatch');
    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job): true {
        expect($job->payload)->toHaveKey('action', 'created')
            ->and($job->payload)->not->toHaveKey('reservation');

        return true;
    });
});
