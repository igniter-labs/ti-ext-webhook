<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook;

use Igniter\Flame\Support\Facades\Igniter;
use Igniter\System\Classes\BaseExtension;
use IgniterLabs\Webhook\ApiResources\Webhooks;
use IgniterLabs\Webhook\AutomationRules\Actions\SendWebhook;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Console\Cleanup;
use IgniterLabs\Webhook\Http\Requests\SettingsRequest;
use IgniterLabs\Webhook\Models\Outgoing;
use IgniterLabs\Webhook\Models\Settings;
use IgniterLabs\Webhook\Models\WebhookLog;
use IgniterLabs\Webhook\WebhookEvents\Category;
use IgniterLabs\Webhook\WebhookEvents\Customer;
use IgniterLabs\Webhook\WebhookEvents\Menu;
use IgniterLabs\Webhook\WebhookEvents\Order;
use IgniterLabs\Webhook\WebhookEvents\Reservation;
use IgniterLabs\Webhook\WebhookEvents\Table;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Event;
use Override;

/**
 * Webhook Extension Information File
 */
class Extension extends BaseExtension
{
    protected array $morphMap = [
        'outgoing_webhook' => Outgoing::class,
    ];

    public $singletons = [
        WebhookManager::class,
    ];

    #[Override]
    public function register(): void
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__.'/../config/webhook-server.php', 'webhook-server');

        $this->registerConsoleCommand('webhook.cleanup', Cleanup::class);
    }

    #[Override]
    public function boot(): void
    {
        $this->bootWebhookServer();

        if (WebhookManager::isConfigured()) {
            WebhookManager::applyWebhookConfigValues();
            WebhookManager::bindWebhookEvents();
            Igniter::prunableModel(WebhookLog::class);
        }
    }

    #[Override]
    public function registerSettings(): array
    {
        return [
            'settings' => [
                'label' => 'Webhooks Settings',
                'description' => 'Configure authentication, signature key settings for the Webhooks extension.',
                'icon' => 'fa fa-cog',
                'model' => Settings::class,
                'request' => SettingsRequest::class,
                'permissions' => ['IgniterLabs.Webhook.ManageSetting'],
            ],
        ];
    }

    /**
     * Registers any admin permissions used by this extension.
     */
    #[Override]
    public function registerPermissions(): array
    {
        return [
            'IgniterLabs.Webhook.ManageSetting' => [
                'description' => 'Manage Webhook settings',
                'group' => 'igniter::system.permissions.name',
            ],
        ];
    }

    #[Override]
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

    public function registerSchedule(Schedule $schedule): void
    {
        $schedule->command('webhook:cleanup')->name('Webhook Log Cleanup')->daily();
    }

    public function registerWebhookEvents(): array
    {
        return [
            'category' => Category::class,
            'customer' => Customer::class,
            'menu' => Menu::class,
            'order' => Order::class,
            'reservation' => Reservation::class,
            'table' => Table::class,
        ];
    }

    public function registerApiResources(): array
    {
        return [
            'webhooks' => [
                'controller' => Webhooks::class,
                'name' => 'Webhooks',
                'description' => 'An API resource for webhooks',
                'actions' => [
                    'store:admin', 'update:admin', 'destroy:admin',
                ],
            ],
        ];
    }

    public function registerAutomationRules(): array
    {
        return [
            'events' => [],
            'actions' => [
                SendWebhook::class,
            ],
            'conditions' => [],
        ];
    }

    protected function bootWebhookServer()
    {
        Event::listen('igniterlabs.webhook.succeeded', function($eventPayload): void {
            WebhookLog::createLog($eventPayload, true);
        });

        Event::listen('igniterlabs.webhook.failed', function($eventPayload): void {
            WebhookLog::createLog($eventPayload);
        });
    }
}
