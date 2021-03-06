<?php

namespace IgniterLabs\Webhook;

use IgniterLabs\Webhook\Classes\WebhookManager;
use IgniterLabs\Webhook\Models\WebhookLog;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\WebhookClient\Exceptions\InvalidConfig;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookConfigRepository;
use Spatie\WebhookServer\Events\WebhookCallFailedEvent;
use Spatie\WebhookServer\Events\WebhookCallSucceededEvent;
use System\Classes\BaseExtension;

/**
 * Webhook Extension Information File
 */
class Extension extends BaseExtension
{
    /**
     * Register method, called when the extension is first registered.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/webhook-server.php', 'webhook-server');
        $this->mergeConfigFrom(__DIR__.'/config/webhook-client.php', 'webhook-client');
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'outgoing_webhook' => 'IgniterLabs\Webhook\Models\Outgoing',
            'incoming_webhook' => 'IgniterLabs\Webhook\Models\Incoming',
        ]);

        $this->bootWebhookServer();

        $this->bootWebhookClient();

        if (WebhookManager::isConfigured())
            WebhookManager::bindWebhookEvents();
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'Webhooks Settings',
                'description' => 'Configure authentication, signature key settings for the Webhooks extension.',
                'icon' => 'fa fa-cog',
                'model' => 'Igniterlabs\Webhook\Models\Settings',
                'permissions' => ['IgniterLabs.Webhook.ManageSetting'],
            ],
        ];
    }

    /**
     * Registers any admin permissions used by this extension.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'IgniterLabs.Webhook.ManageSetting' => [
                'description' => 'Manage Webhook settings',
                'group' => 'module',
            ],
        ];
    }

    public function registerNavigation()
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

    public function registerWebhookTypes()
    {
        return [
            'events' => [
                'category' => \IgniterLabs\Webhook\WebhookEvents\Category::class,
                'customer' => \IgniterLabs\Webhook\WebhookEvents\Customer::class,
                'menu' => \IgniterLabs\Webhook\WebhookEvents\Menu::class,
                'order' => \IgniterLabs\Webhook\WebhookEvents\Order::class,
                'reservation' => \IgniterLabs\Webhook\WebhookEvents\Reservation::class,
                'table' => \IgniterLabs\Webhook\WebhookEvents\Table::class,
            ],
            'actions' => [
                'category' => \IgniterLabs\Webhook\WebhookActions\Category::class,
                'customer' => \IgniterLabs\Webhook\WebhookActions\Customer::class,
                'menu' => \IgniterLabs\Webhook\WebhookActions\Menu::class,
                'order' => \IgniterLabs\Webhook\WebhookActions\Order::class,
                'reservation' => \IgniterLabs\Webhook\WebhookActions\Reservation::class,
                'table' => \IgniterLabs\Webhook\WebhookActions\Table::class,
            ],
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

    protected function bootWebhookServer()
    {
        Event::listen(WebhookCallSucceededEvent::class, function ($event) {
            WebhookLog::createLog($event, TRUE);
        });

        Event::listen(WebhookCallFailedEvent::class, function ($event) {
            WebhookLog::createLog($event);
        });
    }

    protected function bootWebhookClient()
    {
        $this->app->singleton(WebhookConfigRepository::class, function () {
            $configRepository = new WebhookConfigRepository();

            collect(config('webhook-client.configs'))
                ->map(function (array $config) {
                    return new WebhookConfig($config);
                })
                ->each(function (WebhookConfig $webhookConfig) use ($configRepository) {
                    $configRepository->addConfig($webhookConfig);
                });

            return $configRepository;
        });

        $this->app->bind(WebhookConfig::class, function () {
            $configName = Str::after(Route::currentRouteName(), 'webhook-client-');

            $webhookConfig = app(WebhookConfigRepository::class)->getConfig($configName);

            if (is_null($webhookConfig)) {
                throw InvalidConfig::couldNotFindConfig($configName);
            }

            return $webhookConfig;
        });
    }
}
