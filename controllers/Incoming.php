<?php

namespace IgniterLabs\Webhook\Controllers;

use Admin\Facades\AdminMenu;

/**
 * Incoming Webhooks Admin Controller
 */
class Incoming extends \Admin\Classes\AdminController
{
    public $implement = [
        'Admin\Actions\FormController',
        'Admin\Actions\ListController',
    ];

    public $listConfig = [
        'list' => [
            'model' => 'IgniterLabs\Webhook\Models\Incoming',
            'title' => 'lang:igniterlabs.webhook::default.incoming.text_title',
            'emptyMessage' => 'lang:igniterlabs.webhook::default.incoming.text_empty',
            'defaultSort' => ['id', 'DESC'],
            'configFile' => 'incoming',
        ],
    ];

    public $formConfig = [
        'name' => 'lang:igniterlabs.webhook::default.incoming.text_form_name',
        'model' => 'IgniterLabs\Webhook\Models\Incoming',
        'request' => 'IgniterLabs\Webhook\Requests\Incoming',
        'create' => [
            'title' => 'lang:admin::lang.form.create_title',
            'redirect' => 'igniterlabs/webhook/incoming/edit/{id}',
            'redirectClose' => 'igniterlabs/webhook/incoming',
        ],
        'edit' => [
            'title' => 'lang:admin::lang.form.edit_title',
            'redirect' => 'igniterlabs/webhook/incoming/edit/{id}',
            'redirectClose' => 'igniterlabs/webhook/incoming',
        ],
        'preview' => [
            'title' => 'lang:admin::lang.form.preview_title',
            'redirect' => 'igniterlabs/webhook/incoming',
        ],
        'delete' => [
            'redirect' => 'igniterlabs/webhook/incoming',
        ],
        'configFile' => 'incoming',
    ];

    protected $requiredPermissions = 'IgniterLabs.Webhook.*';

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('tools', 'webhooks');
    }

    public function formExtendFields($form)
    {
        if ($form->context != 'create') {
            $field = $form->getField('action');
            $field->disabled = TRUE;
        }
    }
}
