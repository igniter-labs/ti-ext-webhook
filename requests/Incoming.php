<?php

namespace IgniterLabs\Webhook\Requests;

use System\Classes\FormRequest;

class Incoming extends FormRequest
{
    public function attributes()
    {
        return [
            'name' => lang('lang:admin::lang.label_name'),
            'is_active' => lang('lang:admin::lang.label_status'),
            'action' => lang('lang:igniterlabs.webhook::default.incoming.label_action'),
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required|between:2,128',
            'is_active' => 'required|boolean',
            'action' => 'required|string',
        ];
    }
}
