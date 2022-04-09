<?php

namespace IgniterLabs\Webhook\Controllers;

use Admin\Facades\AdminMenu;
use Igniter\Flame\Exception\ApplicationException;
use IgniterLabs\Webhook\Classes\WebhookManager;

/**
 * Webhooks Admin Controller
 */
class Outgoing extends \Admin\Classes\AdminController
{
    public $implement = [
        \Admin\Actions\FormController::class,
        \Admin\Actions\ListController::class,
    ];

    public $listConfig = [
        'list' => [
            'model' => \IgniterLabs\Webhook\Models\Outgoing::class,
            'title' => 'lang:igniterlabs.webhook::default.outgoing.text_title',
            'emptyMessage' => 'lang:igniterlabs.webhook::default.outgoing.text_empty',
            'defaultSort' => ['id', 'DESC'],
            'configFile' => 'outgoing',
        ],
    ];

    public $formConfig = [
        'name' => 'lang:igniterlabs.webhook::default.outgoing.text_form_name',
        'model' => \IgniterLabs\Webhook\Models\Outgoing::class,
        'request' => \IgniterLabs\Webhook\Requests\Outgoing::class,
        'create' => [
            'title' => 'lang:admin::lang.form.create_title',
            'redirect' => 'igniterlabs/webhook/outgoing/edit/{id}',
            'redirectClose' => 'igniterlabs/webhook/outgoing',
        ],
        'edit' => [
            'title' => 'lang:admin::lang.form.edit_title',
            'redirect' => 'igniterlabs/webhook/outgoing/edit/{id}',
            'redirectClose' => 'igniterlabs/webhook/outgoing',
        ],
        'preview' => [
            'title' => 'lang:admin::lang.form.preview_title',
            'redirect' => 'igniterlabs/webhook/outgoing',
        ],
        'delete' => [
            'redirect' => 'igniterlabs/webhook/outgoing',
        ],
        'configFile' => 'outgoing',
    ];

    protected $requiredPermissions = 'IgniterLabs.Webhook.*';

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('tools', 'webhooks');
    }

    public function onLoadSetupInstructions()
    {
        if (!$eventCode = post('setup_event_code'))
            throw new ApplicationException('Please choose an event.');

        if (!$eventObj = WebhookManager::instance()->getEventObject($eventCode))
            throw new ApplicationException('Event not found');

        return [
            '[data-partial="setup-instructions-content"]' => $eventObj->renderSetupPartial(),
        ];
    }
}
