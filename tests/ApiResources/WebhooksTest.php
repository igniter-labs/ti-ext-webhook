<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\ApiResources;

use Igniter\User\Models\User;
use IgniterLabs\Webhook\Models\Outgoing;
use Laravel\Sanctum\Sanctum;

it('lists outgoing webhooks', function(): void {
    Sanctum::actingAs(User::factory()->create(), ['webhooks:*']);
    Outgoing::factory()->count(3)->create();

    $this
        ->get(route('igniter.api.webhooks.index'))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('shows a outgoing webhook', function(): void {
    Sanctum::actingAs(User::factory()->create(), ['webhooks:*']);
    $webhook = Outgoing::factory()->create();

    $this
        ->get(route('igniter.api.webhooks.show', [$webhook->getKey()]))
        ->assertOk()
        ->assertJsonPath('data.id', (string)$webhook->getKey())
        ->assertJsonPath('data.attributes.name', $webhook->name);
});

it('create outgoing webhook', function(): void {
    Sanctum::actingAs(User::factory()->create(), ['webhooks:*']);

    $this
        ->post(route('igniter.api.webhooks.store'), [
            'name' => 'Test Webhook',
            'url' => 'http://webhook.tld',
            'events' => ['customers'],
            'is_active' => '1',
            'config_data' => [
                'verify_ssl' => '0',
            ],
        ])
        ->assertCreated()
        ->assertJson([
            'data' => [
                'attributes' => [
                    'name' => 'Test Webhook',
                    'url' => 'http://webhook.tld',
                    'events' => ['customers'],
                    'is_active' => true,
                ],
            ],
        ]);
});

it('updates outgoing webhook', function(): void {
    Sanctum::actingAs(User::factory()->create(), ['webhooks:*']);
    $webhook = Outgoing::factory()->create();

    $this
        ->patch(route('igniter.api.webhooks.update', [$webhook->getKey()]), [
            'name' => 'Updated Webhook',
            'url' => 'http://updated-webhook.tld',
            'events' => ['orders'],
            'is_active' => '0',
            'config_data' => [
                'verify_ssl' => '1',
            ],
        ])
        ->assertOk()
        ->assertJson([
            'data' => [
                'attributes' => [
                    'name' => 'Updated Webhook',
                    'url' => 'http://updated-webhook.tld',
                    'events' => ['orders'],
                    'is_active' => false,
                ],
            ],
        ]);
});

it('deletes outgoing webhook', function(): void {
    Sanctum::actingAs(User::factory()->create(), ['webhooks:*']);
    $webhook = Outgoing::factory()->create();

    $this
        ->delete(route('igniter.api.webhooks.destroy', [$webhook->getKey()]))
        ->assertNoContent();

    expect(Outgoing::find($webhook->getKey()))->toBeNull();
});

it('blocks unauthorized access', function() {
    $this
        ->get(route('igniter.api.webhooks.index'))
        ->assertUnauthorized();

    $this
        ->post(route('igniter.api.webhooks.store'), [])
        ->assertUnauthorized();

    $this
        ->patch(route('igniter.api.webhooks.update', [1]), [])
        ->assertUnauthorized();

    $this
        ->delete(route('igniter.api.webhooks.destroy', [1]))
        ->assertUnauthorized();
});
