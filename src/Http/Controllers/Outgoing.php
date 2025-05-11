<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Http\Controllers;

use Igniter\Admin\Classes\AdminController;
use Igniter\Admin\Facades\AdminMenu;
use Igniter\Admin\Http\Actions\FormController;
use Igniter\Admin\Http\Actions\ListController;
use Igniter\Flame\Exception\FlashException;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Http\Requests\OutgoingRequest;

/**
 * Webhooks Admin Controller
 */
class Outgoing extends AdminController
{
    public array $implement = [
        FormController::class,
        ListController::class,
    ];

    public array $listConfig = [
        'list' => [
            'model' => \IgniterLabs\Webhook\Models\Outgoing::class,
            'title' => 'lang:igniterlabs.webhook::default.outgoing.text_title',
            'emptyMessage' => 'lang:igniterlabs.webhook::default.outgoing.text_empty',
            'defaultSort' => ['id', 'DESC'],
            'configFile' => 'outgoing',
        ],
    ];

    public array $formConfig = [
        'name' => 'lang:igniterlabs.webhook::default.outgoing.text_form_name',
        'model' => \IgniterLabs\Webhook\Models\Outgoing::class,
        'request' => OutgoingRequest::class,
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
            'back' => 'igniterlabs/webhook/outgoing',
        ],
        'delete' => [
            'redirect' => 'igniterlabs/webhook/outgoing',
        ],
        'configFile' => 'outgoing',
    ];

    protected null|string|array $requiredPermissions = 'IgniterLabs.Webhook.*';

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('tools', 'webhooks');
    }

    public function onLoadSetupInstructions(): array
    {
        throw_unless($eventCode = post('setup_event_code'),
            new FlashException('Please choose an event.')
        );

        throw_unless($eventObj = resolve(WebhookManager::class)->getEventObject($eventCode),
            new FlashException('Event not found')
        );

        return [
            '[data-partial="setup-instructions-content"]' => $eventObj->renderSetupPartial(),
        ];
    }
}
