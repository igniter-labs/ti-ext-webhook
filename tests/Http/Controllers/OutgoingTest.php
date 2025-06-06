<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\Http\Controllers;

use IgniterLabs\Webhook\Models\Outgoing;

it('loads outgoing webhooks page', function(): void {
    actingAsSuperUser()
        ->get(route('igniterlabs.webhook.outgoing'))
        ->assertOk();
});

it('loads create outgoing webhook page', function(): void {
    actingAsSuperUser()
        ->get(route('igniterlabs.webhook.outgoing', ['slug' => 'create']))
        ->assertOk();
});

it('loads edit outgoing webhook page', function(): void {
    $outgoing = Outgoing::factory()->create();

    actingAsSuperUser()
        ->get(route('igniterlabs.webhook.outgoing', ['slug' => 'edit/'.$outgoing->getKey()]))
        ->assertOk();
});

it('creates outgoing webhook', function(): void {
    actingAsSuperUser()
        ->post(route('igniterlabs.webhook.outgoing', ['slug' => 'create']), [
            'Outgoing' => [
                'name' => 'Webhook for created customer',
                'is_active' => '1',
                'url' => 'https://example.com/webhook',
                'config_data' => [
                    'verify_ssl' => '1',
                ],
                'events' => ['customers'],
            ],
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-IGNITER-REQUEST-HANDLER' => 'onSave',
        ]);

    expect(Outgoing::where('name', 'Webhook for created customer')->exists())->toBeTrue();
});

it('updates outgoing webhook', function(): void {
    $outgoing = Outgoing::factory()->create();

    actingAsSuperUser()
        ->post(route('igniterlabs.webhook.outgoing', ['slug' => 'edit/'.$outgoing->getKey()]), [
            'Outgoing' => [
                'name' => 'Updated webhook for created customer',
                'is_active' => '1',
                'url' => 'https://example.com/updated-webhook',
                'config_data' => [
                    'verify_ssl' => '1',
                    'secret_key' => 'new-secret-key',
                ],
                'events' => ['customers', 'orders'],
            ],
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-IGNITER-REQUEST-HANDLER' => 'onSave',
        ]);

    expect(Outgoing::where('name', 'Updated webhook for created customer')->exists())->toBeTrue();
});

it('deletes outgoing webhook', function(): void {
    $outgoing = Outgoing::factory()->create();

    actingAsSuperUser()
        ->post(route('igniterlabs.webhook.outgoing', ['slug' => 'edit/'.$outgoing->getKey()]), [], [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-IGNITER-REQUEST-HANDLER' => 'onDelete',
        ]);

    expect(Outgoing::find($outgoing->getKey()))->toBeNull();
});

it('loads setup instructions for a valid event', function(): void {
    $outgoing = Outgoing::factory()->create();

    actingAsSuperUser()
        ->post(route('igniterlabs.webhook.outgoing', ['slug' => 'edit/'.$outgoing->getKey()]), [
            'setup_event_code' => 'customer',
        ], [
            'X-Requested-With' => 'XMLHttpRequest',
            'X-IGNITER-REQUEST-HANDLER' => 'onLoadSetupInstructions',
        ])
        ->assertJsonStructure(['[data-partial="setup-instructions-content"]']);
});
