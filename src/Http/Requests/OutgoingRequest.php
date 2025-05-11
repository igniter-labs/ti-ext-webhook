<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Http\Requests;

use Igniter\System\Classes\FormRequest;
use Override;

class OutgoingRequest extends FormRequest
{
    #[Override]
    public function attributes()
    {
        return [
            'name' => lang('lang:admin::lang.label_name'),
            'is_active' => lang('lang:admin::lang.label_status'),
            'url' => lang('lang:igniterlabs.webhook::default.outgoing.label_url'),
            'config_data.secret_key' => lang('lang:igniterlabs.webhook::default.outgoing.label_secret'),
            'config_data.content_type' => lang('lang:igniterlabs.webhook::default.outgoing.label_content_type'),
            'config_data.verify_ssl' => lang('lang:igniterlabs.webhook::default.outgoing.label_verify_ssl'),
            'events.*' => lang('lang:igniterlabs.webhook::default.outgoing.label_events'),
        ];
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'between:2,128'],
            'is_active' => ['required', 'boolean'],
            'url' => ['required', 'url'],
            'config_data.secret_key' => ['nullable', 'string'],
            'config_data.content_type' => ['required', 'string'],
            'config_data.verify_ssl' => ['required', 'boolean'],
            'events.*' => ['required', 'string'],
        ];
    }
}
