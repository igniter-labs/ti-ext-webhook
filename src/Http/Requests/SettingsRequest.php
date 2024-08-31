<?php

namespace IgniterLabs\Webhook\Http\Requests;

use Igniter\System\Classes\FormRequest;

class SettingsRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'enable_authentication' => lang('igniterlabs.webhook::default.label_enable_authentication'),
            'verify_ssl' => lang('igniterlabs.webhook::default.label_verify_ssl'),
            'timeout_in_seconds' => lang('igniterlabs.webhook::default.label_timeout_in_seconds'),
            'tries' => lang('igniterlabs.webhook::default.label_tries'),
            'server_signature_header' => lang('igniterlabs.webhook::default.label_server_signature_header'),
            'headers' => lang('igniterlabs.webhook::default.label_headers'),
        ];
    }

    public function rules(): array
    {
        return [
            'enable_authentication' => ['required', 'integer'],
            'verify_ssl' => ['required', 'integer'],
            'timeout_in_seconds' => ['required', 'integer'],
            'tries' => ['required', 'integer'],
            'server_signature_header' => ['required', 'string'],
            'headers' => ['nullable', 'array'],
        ];
    }
}
