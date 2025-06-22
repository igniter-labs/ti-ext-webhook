<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\Http\Requests;

use IgniterLabs\Webhook\Http\Requests\OutgoingRequest;

it('defines attributes for validation error messages', function(): void {
    $attributes = (new OutgoingRequest)->attributes();

    expect($attributes)->toBeArray()
        ->toHaveKey('name', lang('lang:admin::lang.label_name'))
        ->toHaveKey('is_active', lang('lang:admin::lang.label_status'))
        ->toHaveKey('url', lang('lang:igniterlabs.webhook::default.outgoing.label_url'))
        ->toHaveKey('config_data.secret_key', lang('lang:igniterlabs.webhook::default.outgoing.label_secret'))
        ->toHaveKey('config_data.verify_ssl', lang('lang:igniterlabs.webhook::default.outgoing.label_verify_ssl'))
        ->toHaveKey('events.*', lang('lang:igniterlabs.webhook::default.outgoing.label_events'));
});

it('defines validation rules', function(): void {
    $rules = (new OutgoingRequest)->rules();

    expect($rules)->toBeArray()
        ->and($rules)->toHaveKey('name', ['required', 'between:2,128'])
        ->and($rules)->toHaveKey('is_active', ['required', 'boolean'])
        ->and($rules)->toHaveKey('url', ['required', 'url'])
        ->and($rules)->toHaveKey('config_data.secret_key', ['nullable', 'string'])
        ->and($rules)->toHaveKey('config_data.verify_ssl', ['required', 'boolean'])
        ->and($rules)->toHaveKey('events.*', ['required', 'string']);
});
