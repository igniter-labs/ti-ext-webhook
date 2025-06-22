<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Models;

use Igniter\Flame\Database\Factories\HasFactory;
use Igniter\Flame\Database\Model;
use Igniter\Flame\Exception\SystemException;
use IgniterLabs\Webhook\Classes\BaseEvent;
use IgniterLabs\Webhook\Classes\WebhookManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Spatie\WebhookServer\WebhookCall;

/**
 * Outgoing Webhook Model
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property array $events
 * @property array $config_data
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 * @method setEventPayload(array $payload)
 */
class Outgoing extends Model
{
    use HasFactory;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'igniterlabs_webhook_outgoing';

    public $timestamps = true;

    protected $guarded = [];

    public $relation = [
        'morphMany' => [
            'deliveries' => [WebhookLog::class, 'name' => 'webhook', 'delete' => true],
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
     * @return Collection
     */
    public static function listWebhooksForEvent($eventCode)
    {
        return self::query()->where('is_active', true)->get()->filter(fn($model): bool => in_array($eventCode, $model->events ?? []));
    }

    public function getDropdownOptions(): array
    {
        return array_map(fn(BaseEvent $event): array => [$event->eventName(), $event->eventDescription()], resolve(WebhookManager::class)->listEventObjects());
    }

    /**
     * Kicks off this outgoing webhook.
     * @param string $actionCode
     * @param string $eventCode
     */
    public function dispatchWebhook($actionCode, $eventCode): void
    {
        if ((string)$this->url === '') {
            throw new SystemException('Missing a webhook payload URL.');
        }

        $options = $this->config_data ?? [];
        $secretKey = array_get($options, 'secret_key');

        $webhookJob = WebhookCall::create()->url($this->url);

        $webhookJob->verifySsl((bool)array_get($options, 'verify_ssl', true));

        strlen((string)$secretKey) !== 0 ? $webhookJob->useSecret($secretKey) : $webhookJob->doNotSign();

        $payload = ['action' => $actionCode] + $this->getEventObject()->getEventPayload();
        $webhookJob->payload($payload);

        $webhookJob->meta([
            'webhook_id' => $this->getKey(),
            'webhook_type' => $this->getMorphClass(),
            'name' => $this->name,
            'event_code' => $eventCode,
        ]);

        Event::dispatch('igniterlabs.webhook.beforeDispatch', [$webhookJob, $this]);

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
     */
    public function applyEventClass($className): bool
    {
        if ($className && !$this->isClassExtendedWith($className)) {
            $this->extendClassWith($className);
        }

        $this->eventClassName = $className;

        return $this->isClassExtendedWith($className);
    }

    /**
     * Returns the event class extension object.
     * @return BaseEvent
     */
    public function getEventObject(): mixed
    {
        return $this->asExtension($this->eventClassName);
    }
}
