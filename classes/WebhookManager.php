<?php

namespace IgniterLabs\Webhook\Classes;

use Igniter\Flame\Exception\ApplicationException;
use Igniter\Flame\Traits\Singleton;
use IgniterLabs\Webhook\Models\Incoming;
use IgniterLabs\Webhook\Models\Outgoing;
use IgniterLabs\Webhook\Models\Settings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Response;
use System\Classes\ExtensionManager;

class WebhookManager
{
    use Singleton;

    protected $webhookTypes;

    /**
     * @var array A cache of webhook events & actions.
     */
    protected $webhookTypesCache = [];

    /**
     * @var array Cache of registration callbacks.
     */
    protected $webhookTypesCallbacks = [];

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

        Config::set('webhook-client.configs.0.signing_secret', Settings::get('client_signing_secret', Config::get('webhook-client.configs.0.signing_secret')));
        Config::set('webhook-client.configs.0.signature_header_name', Settings::get('client_signature_header', Config::get('webhook-client.configs.0.signature_header_name')));
    }

    //
    //
    //

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
            self::instance()->runWebhookEvent($eventCode, $actionCode, $payload);
        });
    }

    public function runWebhookEvent($eventCode, $actionCode, array $payload)
    {
        $eventClass = $this->getEventClass($eventCode);
        if (!class_exists($eventClass))
            throw new ApplicationException('Webhook event class ['.$eventClass.'] not found');

        $models = Outgoing::listWebhooksForEvent($eventCode);

        $models->each(function (Outgoing $model) use ($eventClass, $actionCode, $payload) {
            if ($model->applyEventClass($eventClass)) {
                $model->setEventPayload($payload);
                $model->dispatchWebhook($actionCode);
            }
        });
    }

    //
    //
    //

    /**
     * Executes an entry point for incoming webhook, defined in routes.php file.
     *
     * @param string $actionCode Incoming webhook action
     * @param string $actionHash Incoming webhook hash
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function runEntryPoint($actionCode, $actionHash)
    {
        if ($webhookAction = Incoming::findByCodeHash($actionCode, $actionHash)) {
            return $webhookAction->processWebHook();
        }

        return Response::json(['message' => 'access_forbidden'], '403');
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
     * Returns a single action class name
     *
     * @param $actionCode
     * @return string
     */
    public function getActionClass($actionCode)
    {
        return array_get($this->listActions(), $actionCode, null);
    }

    /**
     * Returns a list of registered webhooks events.
     * @return array Array keys are class names.
     */
    public function listEvents()
    {
        return array_get($this->listWebhookTypes(), 'events', []);
    }

    /**
     * Returns a list of registered webhooks actions.
     * @return array Array keys are class names.
     */
    public function listActions()
    {
        return array_get($this->listWebhookTypes(), 'actions', []);
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
     * Returns a single action object
     *
     * @param $actionCode
     * @return \IgniterLabs\Webhook\Classes\BaseAction
     */
    public function getActionObject($actionCode)
    {
        return array_get($this->listActionObjects(), $actionCode, null);
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
     * Returns a list of registered webhooks actions.
     * @return array Array keys are class names.
     */
    public function listActionObjects()
    {
        $results = [];
        foreach ($this->listActions() as $code => $className) {
            if (!class_exists($className)) continue;
            $results[$code] = new $className;
        }

        return $results;
    }

    public function listWebhookTypes()
    {
        if ($this->webhookTypesCache) {
            return $this->webhookTypesCache;
        }

        foreach ($this->webhookTypesCallbacks as $callback) {
            $callback($this);
        }

        $webhookTypesBundles = ExtensionManager::instance()->getRegistrationMethodValues('registerWebhookTypes');
        foreach ($webhookTypesBundles as $owner => $definitions) {
            if (!is_array($definitions))
                continue;

            $this->registerWebhookType($definitions);
        }

        return $this->webhookTypesCache = $this->webhookTypes;
    }

    public function registerWebhookType($definitions)
    {
        if (!$this->webhookTypes) {
            $this->webhookTypes = [];
        }

        foreach ($definitions as $index => $definition) {
            if (!is_string($index)) continue;
            if (!is_array($definition)) continue;
            $this->webhookTypes[$index] = array_merge($this->webhookTypes[$index] ?? [], $definition);
        }
    }

    /**
     * Manually registers webhooks events & actions for consideration.
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
        $this->webhookTypesCallbacks[] = $definitions;
    }
}