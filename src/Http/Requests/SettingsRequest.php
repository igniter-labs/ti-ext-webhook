<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Http\Requests;

use Igniter\System\Classes\FormRequest;
use Override;

class SettingsRequest extends FormRequest
{
    #[Override]
    public function attributes(): array
    {
        return [
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
            'verify_ssl' => ['required', 'integer'],
            'timeout_in_seconds' => ['required', 'integer'],
            'tries' => ['required', 'integer'],
            'server_signature_header' => ['required', 'string'],
            'headers' => ['nullable', 'array'],
        ];
    }
}
