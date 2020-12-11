<?php

namespace IgniterLabs\Webhook\Models;

use Igniter\Flame\Exception\ApplicationException;
use IgniterLabs\Webhook\Classes\BaseEvent;
use IgniterLabs\Webhook\Classes\WebhookManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Model;
use Spatie\WebhookServer\WebhookCall;

/**
 * Outgoing Webhook Model
 *
 * @method setEventPayload(array $payload)
 */
class Outgoing extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'igniterlabs_webhook_outgoing';

    public $timestamps = TRUE;

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    public $relation = [
        'morphMany' => [
            'deliveries' => ['IgniterLabs\Webhook\Models\WebhookLog', 'name' => 'webhook', 'delete' => TRUE],
        ],
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'events' => 'array',
        'config_data' => 'array',
    ];

    protected $eventClassName;

    /**
     * @param string $eventCode
     * @return \Illuminate\Support\Collection
     */
    public static function listWebhooksForEvent($eventCode)
    {
        return self::where('is_active', TRUE)->get()->filter(function ($model) use ($eventCode) {
            return in_array($eventCode, $model->events ?? []);
        });
    }

    public function getDropdownOptions()
    {
        return array_map(function (BaseEvent $event) {
            return [$event->eventName(), $event->eventDescription()];
        }, WebhookManager::instance()->listEventObjects());
    }

    public function getContentTypeOptions()
    {
        return [
            'application/json' => 'application/json',
            'application/x-www-form-urlencoded' => 'application/x-www-form-urlencoded',
        ];
    }

    /**
     * Kicks off this outgoing webhook.
     * @param string $actionCode
     */
    public function dispatchWebhook($actionCode)
    {
        if (!strlen($this->url))
            throw new ApplicationException('Missing a webhook payload URL.');

        $options = $this->config_data ?? [];
        $secretKey = array_get($options, 'secret_key');
        $contentType = array_get($options, 'content_type');

        $webhookJob = WebhookCall::create()->url($this->url);

        $webhookJob->verifySsl((bool)array_get($options, 'verify_ssl', TRUE));

        strlen($secretKey) ? $webhookJob->useSecret($secretKey) : $webhookJob->doNotSign();

        $webhookJob->postAsJson($contentType !== 'application/x-www-form-urlencoded');

        $payload = ['action' => $actionCode] + $this->getEventObject()->getEventPayload();
        $webhookJob->payload($payload);

        Event::fire('igniterlabs.webhook.beforeDispatch', [$webhookJob]);

        WebhookLog::addLog($this, app('request'));

        $webhookJob->dispatch();
    }

    //
    // Events
    //

    protected function beforeCreate()
    {
        $configData = $this->config_data ?? [];
        if (!array_get($configData, 'secret_key')) {
            $configData['secret_key'] = Str::random(16);
        }

        $this->config_data = $configData;
    }

    //
    // Manager
    //

    /**
     * Extends this class with the event class
     * @param string $className Class name
     * @return bool
     */
    public function applyEventClass($className)
    {
        if (!$className)
            return FALSE;

        if (!$this->isClassExtendedWith($className)) {
            $this->extendClassWith($className);
        }

        $this->eventClassName = $className;

        return TRUE;
    }

    /**
     * Returns the event class extension object.
     * @return \IgniterLabs\Webhook\Classes\BaseEvent
     */
    public function getEventObject()
    {
        return $this->asExtension($this->eventClassName);
    }
}