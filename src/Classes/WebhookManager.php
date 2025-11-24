<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Classes;

use Igniter\Flame\Support\Facades\Igniter;
use Igniter\System\Classes\ExtensionManager;
use IgniterLabs\Webhook\Models\Outgoing;
use IgniterLabs\Webhook\Models\Settings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

class WebhookManager
{
    protected array $webhookEvents = [];

    /**
     * @var array A cache of webhook events.
     */
    protected array $webhookEventsCache = [];

    /**
     * @var array Cache of registration callbacks.
     */
    protected array $webhookEventsCallbacks = [];

    protected bool $booted = false;

    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        if (!$this->isConfigured()) {
            return;
        }

        $this->applyWebhookConfigValues();
        $this->bindWebhookEvents();

        $this->booted = true;
    }

    public function applyWebhookConfigValues(): void
    {
        Config::set('webhook-server.verify_ssl', Settings::get('verify_ssl') ?: Config::get('webhook-server.verify_ssl'));
        Config::set('webhook-server.timeout_in_seconds', Settings::get('timeout_in_seconds') ?: Config::get('webhook-server.timeout_in_seconds'));
        Config::set('webhook-server.tries', Settings::get('tries') ?: Config::get('webhook-server.tries'));
        Config::set('webhook-server.signature_header_name', Settings::get('server_signature_header') ?: Config::get('webhook-server.signature_header_name'));
        Config::set('webhook-server.headers', array_merge(Config::get('webhook-server.headers'), Settings::getHeaders() ?: []));
    }

    public function isConfigured(): bool
    {
        return Igniter::hasDatabase()
            && Schema::hasTable('igniterlabs_webhook_outgoing');
    }

    public function isBooted(): bool
    {
        return $this->booted;
    }

    public function bindWebhookEvents(): void
    {
        collect($this->listEvents())->each(function($eventClass, $eventCode): void {
            if (method_exists($eventClass, 'registerEventListeners')) {
                $eventListeners = $eventClass::registerEventListeners();
                foreach ($eventListeners as $actionCode => $systemEvent) {
                    $this->bindWebhookEvent($systemEvent, $eventCode, $actionCode, $eventClass);
                }
            }
        });
    }

    public function bindWebhookEvent($systemEvent, $eventCode, $actionCode, $eventClass): void
    {
        Event::listen($systemEvent, function() use ($eventCode, $actionCode, $eventClass): void {
            if (method_exists($eventClass, 'makePayloadFromEvent')) {
                $payload = $eventClass::makePayloadFromEvent(func_get_args(), $actionCode);
                if (array_filter($payload ?? [])) {
                    $this->runWebhookEvent($eventCode, $actionCode, $payload);
                }
            }
        });
    }

    public function runWebhookEvent($eventCode, $actionCode, array $payload): void
    {
        throw_unless(class_exists($eventClass = $this->getEventClass($eventCode)),
            new InvalidArgumentException('Webhook event class ['.$eventClass.'] not found'),
        );

        Outgoing::listWebhooksForEvent($eventCode)
            ->each(function(Outgoing $model) use ($eventClass, $eventCode, $actionCode, $payload): void {
                if ($model->applyEventClass($eventClass)) {
                    $model->setEventPayload($payload);
                    $model->dispatchWebhook($actionCode, $eventCode);
                }
            });
    }

    //
    // Registration
    //

    /**
     * Returns a single event class name
     *
     * @return string
     */
    public function getEventClass($eventCode)
    {
        return array_get($this->listEvents(), $eventCode);
    }

    /**
     * Returns a single event object
     *
     * @return BaseEvent
     */
    public function getEventObject($eventCode)
    {
        return array_get($this->listEventObjects(), $eventCode);
    }

    /**
     * Returns a list of registered webhooks events.
     * @return array Array keys are class names.
     */
    public function listEventObjects()
    {
        $results = [];
        foreach ($this->listEvents() as $code => $className) {
            if (class_exists($className)) {
                $results[$code] = new $className;
            }
        }

        return $results;
    }

    /**
     * Returns a list of registered webhooks events.
     * @return array Array keys are class names.
     */
    public function listEvents()
    {
        if ($this->webhookEventsCache) {
            return $this->webhookEventsCache;
        }

        foreach ($this->webhookEventsCallbacks as $callback) {
            $callback($this);
        }

        $webhookEventsBundles = resolve(ExtensionManager::class)->getRegistrationMethodValues('registerWebhookEvents');
        foreach ($webhookEventsBundles as $definitions) {
            if (is_array($definitions)) {
                $this->registerWebhookEvent($definitions);
            }
        }

        return $this->webhookEventsCache = $this->webhookEvents;
    }

    public function registerWebhookEvent($definitions): void
    {
        if (!$this->webhookEvents) {
            $this->webhookEvents = [];
        }

        foreach ($definitions as $eventCode => $eventClass) {
            $this->webhookEvents[$eventCode] = $eventClass;
        }
    }

    /**
     * Manually registers webhooks events for consideration.
     * Usage:
     * <pre>
     *   ProviderManager::registerCallback(function($manager){
     *       $manager->registerProviders([
     *
     *       ]);
     *   });
     * </pre>
     */
    public function registerCallback(callable $definitions): void
    {
        $this->webhookEventsCallbacks[] = $definitions;
    }
}
