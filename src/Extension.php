<?php

namespace IgniterLabs\Webhook;

use Igniter\Flame\Support\Facades\Igniter;
use Igniter\System\Classes\BaseExtension;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Console\Cleanup;
use IgniterLabs\Webhook\Models\WebhookLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;

/**
 * Webhook Extension Information File
 */
class Extension extends BaseExtension
{
    protected array $morphMap = [
        'outgoing_webhook' => \IgniterLabs\Webhook\Models\Outgoing::class,
    ];

    public $singletons = [
        WebhookManager::class,
    ];

    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__.'/../config/webhook-server.php', 'webhook-server');

        $this->registerConsoleCommand('webhook.cleanup', Cleanup::class);
    }

    public function boot()
    {
        $this->bootWebhookServer();

        if (WebhookManager::isConfigured()) {
            WebhookManager::applyWebhookConfigValues();
            WebhookManager::bindWebhookEvents();
            Igniter::prunableModel(WebhookLog::class);
        }
    }

    public function registerSettings(): array
    {
        return [
            'settings' => [
                'label' => 'Webhooks Settings',
                'description' => 'Configure authentication, signature key settings for the Webhooks extension.',
                'icon' => 'fa fa-cog',
                'model' => \IgniterLabs\Webhook\Models\Settings::class,
                'request' => \IgniterLabs\Webhook\Http\Requests\SettingsRequest::class,
                'permissions' => ['IgniterLabs.Webhook.ManageSetting'],
            ],
        ];
    }

    /**
     * Registers any admin permissions used by this extension.
     */
    public function registerPermissions(): array
    {
        return [
            'IgniterLabs.Webhook.ManageSetting' => [
                'description' => 'Manage Webhook settings',
                'group' => 'igniter::system.permissions.name',
            ],
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'tools' => [
                'child' => [
                    'webhooks' => [
                        'priority' => 50,
                        'class' => 'webhooks',
                        'href' => admin_url('igniterlabs/webhook/outgoing'),
                        'title' => lang('igniterlabs.webhook::default.text_title'),
                        'permission' => 'IgniterLabs.Webhooks.*',
                    ],
                ],
            ],
        ];
    }

    public function registerSchedule(Schedule $schedule)
    {
        $schedule->command('webhook:cleanup')->name('Webhook Log Cleanup')->daily();
    }

    public function registerWebhookEvents()
    {
        return [
            'category' => \IgniterLabs\Webhook\WebhookEvents\Category::class,
            'customer' => \IgniterLabs\Webhook\WebhookEvents\Customer::class,
            'menu' => \IgniterLabs\Webhook\WebhookEvents\Menu::class,
            'order' => \IgniterLabs\Webhook\WebhookEvents\Order::class,
            'reservation' => \IgniterLabs\Webhook\WebhookEvents\Reservation::class,
            'table' => \IgniterLabs\Webhook\WebhookEvents\Table::class,
        ];
    }

    public function registerApiResources()
    {
        return [
            'webhooks' => [
                'controller' => \IgniterLabs\Webhook\ApiResources\Webhooks::class,
                'name' => 'Webhooks',
                'description' => 'An API resource for webhooks',
                'actions' => [
                    'store:admin', 'update:admin', 'destroy:admin',
                ],
            ],
        ];
    }

    public function registerAutomationRules()
    {
        return [
            'events' => [],
            'actions' => [
                \IgniterLabs\Webhook\AutomationRules\Actions\SendWebhook::class,
            ],
            'conditions' => [],
        ];
    }

    protected function bootWebhookServer()
    {
        Event::listen('igniterlabs.webhook.succeeded', function($eventPayload) {
            WebhookLog::createLog($eventPayload, true);
        });

        Event::listen('igniterlabs.webhook.failed', function($eventPayload) {
            WebhookLog::createLog($eventPayload);
        });
    }
}
