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
        ->and($settings['settings']['label'])->toBe('Webhook Settings')
        ->and($settings['settings']['model'])->toBe(Settings::class)
        ->and($settings['settings']['request'])->toBe(SettingsRequest::class)
        ->and($settings['settings']['permissions'])->toBe(['IgniterLabs.Webhook.ManageSetting']);
});

it('registers permissions', function(): void {
    $permissions = $this->extension->registerPermissions();

    expect($permissions)->toHaveKey('IgniterLabs.Webhook.ManageSetting')
        ->and($permissions['IgniterLabs.Webhook.ManageSetting']['group'])->toBe('igniter::system.permissions.name');
});
