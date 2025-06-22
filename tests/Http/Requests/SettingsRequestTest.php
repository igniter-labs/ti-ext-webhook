<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\Http\Requests;

use IgniterLabs\Webhook\Http\Requests\SettingsRequest;

beforeEach(function(): void {
    $this->request = new SettingsRequest;
});

it('defines attributes for validation error messages', function(): void {
    $attributes = (new SettingsRequest)->attributes();

    expect($attributes)->toBeArray()
        ->toHaveKey('verify_ssl', lang('igniterlabs.webhook::default.label_verify_ssl'))
        ->toHaveKey('timeout_in_seconds', lang('igniterlabs.webhook::default.label_timeout_in_seconds'))
        ->toHaveKey('tries', lang('igniterlabs.webhook::default.label_tries'))
        ->toHaveKey('server_signature_header', lang('igniterlabs.webhook::default.label_server_signature_header'))
        ->toHaveKey('headers', lang('igniterlabs.webhook::default.label_headers'));
});

it('defines validation rules', function(): void {
    $rules = (new SettingsRequest)->rules();

    expect($rules)->toBeArray()
        ->and($rules)->toHaveKey('verify_ssl', ['required', 'integer'])
        ->and($rules)->toHaveKey('timeout_in_seconds', ['required', 'integer'])
        ->and($rules)->toHaveKey('tries', ['required', 'integer'])
        ->and($rules)->toHaveKey('server_signature_header', ['required', 'string'])
        ->and($rules)->toHaveKey('headers', ['nullable', 'array']);
});
