<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests;

use IgniterLabs\Webhook\Extension;
use IgniterLabs\Webhook\Http\Requests\SettingsRequest;
use IgniterLabs\Webhook\Models\Settings;

beforeEach(function(): void {
    $this->extension = new Extension(app());
});

it('registers settings', function(): void {
    $settings = $this->extension->registerSettings();

    expect($settings)->toHaveKey('settings')
        ->and($settings['settings']['model'])->toBe(Settings::class)
        ->and($settings['settings']['request'])->toBe(SettingsRequest::class)
        ->and($settings['settings']['permissions'])->toBe(['IgniterLabs.Webhook.ManageSetting']);
});

it('registers permissions', function(): void {
    $permissions = $this->extension->registerPermissions();

    expect($permissions)->toHaveKeys(['IgniterLabs.Webhook.Manage', 'IgniterLabs.Webhook.ManageSetting'])
        ->and($permissions['IgniterLabs.Webhook.ManageSetting']['group'])->toBe('igniter::system.permissions.name');
});

it('shows the webhooks menu to staff with a webhook permission', function(): void {
    expect($this->extension->registerNavigation()['tools']['child']['webhooks']['permission'])->toBe('IgniterLabs.Webhook.*');
});
