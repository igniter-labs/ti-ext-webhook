<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook;

use Igniter\Flame\Support\Facades\Igniter;
use Igniter\System\Classes\BaseExtension;
use IgniterLabs\Webhook\ApiResources\Webhooks;
use IgniterLabs\Webhook\AutomationRules\Actions\SendWebhook;
use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Http\Requests\SettingsRequest;
use IgniterLabs\Webhook\Listeners\WebhookSubscriber;
use IgniterLabs\Webhook\Models\Outgoing;
use IgniterLabs\Webhook\Models\Settings;
use IgniterLabs\Webhook\Models\WebhookLog;
use IgniterLabs\Webhook\WebhookEvents\Category;
use IgniterLabs\Webhook\WebhookEvents\Customer;
use IgniterLabs\Webhook\WebhookEvents\Menu;
use IgniterLabs\Webhook\WebhookEvents\Order;
use IgniterLabs\Webhook\WebhookEvents\Reservation;
use IgniterLabs\Webhook\WebhookEvents\Table;
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

    protected $subscribe = [
        WebhookSubscriber::class,
    ];

    #[Override]
    public function boot(): void
    {
        Igniter::prunableModel(WebhookLog::class);

        $this->app->booted(function(): void {
            resolve(WebhookManager::class)->boot();
        });
    }

    #[Override]
    public function registerSettings(): array
    {
        return [
            'settings' => [
                'label' => 'Webhook Settings',
                'description' => 'Configure webhook settings.',
                'icon' => 'fa fa-link',
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
                    'index:admin', 'show:admin', 'store:admin', 'update:admin', 'destroy:admin',
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
}
