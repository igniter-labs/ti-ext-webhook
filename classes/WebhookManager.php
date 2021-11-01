<?php

namespace IgniterLabs\Webhook\Classes;

use Igniter\Flame\Exception\ApplicationException;
use Igniter\Flame\Traits\Singleton;
use IgniterLabs\Webhook\Models\Outgoing;
use IgniterLabs\Webhook\Models\Settings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use System\Classes\ExtensionManager;

class WebhookManager
{
    use Singleton;

    protected $webhookEvents;

    /**
     * @var array A cache of webhook events.
     */
    protected $webhookEventsCache = [];

    /**
     * @var array Cache of registration callbacks.
     */
    protected $webhookEventsCallbacks = [];

    protected function initialize()
    {
        $this->applyWebhookConfigValues();
    }

    public function applyWebhookConfigValues()
    {
        Config::set('webhook-server.verify_ssl', (bool)Settings::get('verify_ssl', Config::get('webhook-server.verify_ssl')));
        Config::set('webhook-server.timeout_in_seconds', (int)Settings::get('timeout_in_seconds', Config::get('webhook-server.timeout_in_seconds')));
        Config::set('webhook-server.tries', (int)Settings::get('tries', Config::get('webhook-server.tries')));
        Config::set('webhook-server.signature_header_name', Settings::get('server_signature_header', Config::get('webhook-server.signature_header_name')));
//        Config::set('webhook-server.headers', Settings::get('headers', Config::get('webhook-server.headers')));
    }

    //
    //
    //

    public static function isConfigured()
    {
        return app()->hasDatabase()
            && Schema::hasTable('igniterlabs_webhook_outgoing');
    }

    public static function bindWebhookEvents()
    {
        collect(self::instance()->listEvents())->each(function ($eventClass, $eventCode) {
            if (!method_exists($eventClass, 'registerEventListeners'))
                return;

            $eventListeners = $eventClass::registerEventListeners();
            foreach ($eventListeners as $actionCode => $systemEvent) {
                self::bindWebhookEvent($systemEvent, $eventCode, $actionCode, $eventClass);
            }
        });
    }

    public static function bindWebhookEvent($systemEvent, $eventCode, $actionCode, $eventClass)
    {
        Event::listen($systemEvent, function () use ($eventCode, $actionCode, $eventClass) {
            if (!method_exists($eventClass, 'makePayloadFromEvent'))
                return;

            $payload = $eventClass::makePayloadFromEvent(func_get_args(), $actionCode);
            if (is_null($payload))
                return;

            self::instance()->runWebhookEvent($eventCode, $actionCode, $payload);
        });
    }

    public function runWebhookEvent($eventCode, $actionCode, array $payload)
    {
        $eventClass = $this->getEventClass($eventCode);
        if (!class_exists($eventClass))
            throw new ApplicationException('Webhook event class ['.$eventClass.'] not found');

        $models = Outgoing::listWebhooksForEvent($eventCode);

        $models->each(function (Outgoing $model) use ($eventClass, $eventCode, $actionCode, $payload) {
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
     * @param $eventCode
     * @return string
     */
    public function getEventClass($eventCode)
    {
        return array_get($this->listEvents(), $eventCode, null);
    }

    /**
     * Returns a single event object
     *
     * @param $eventCode
     * @return \IgniterLabs\Webhook\Classes\BaseEvent
     */
    public function getEventObject($eventCode)
    {
        return array_get($this->listEventObjects(), $eventCode, null);
    }

    /**
     * Returns a list of registered webhooks events.
     * @return array Array keys are class names.
     */
    public function listEventObjects()
    {
        $results = [];
        foreach ($this->listEvents() as $code => $className) {
            if (!class_exists($className)) continue;
            $results[$code] = new $className;
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

        $webhookEventsBundles = ExtensionManager::instance()->getRegistrationMethodValues('registerWebhookEvents');
        foreach ($webhookEventsBundles as $definitions) {
            if (!is_array($definitions))
                continue;

            $this->registerWebhookEvent($definitions);
        }

        return $this->webhookEventsCache = $this->webhookEvents;
    }

    public function registerWebhookEvent($definitions)
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
     * @param callable $definitions
     */
    public function registerCallback(callable $definitions)
    {
        $this->webhookEventsCallbacks[] = $definitions;
    }
}
