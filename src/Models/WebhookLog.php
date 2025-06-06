<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Models;

use GuzzleHttp\Psr7\Response;
use Igniter\Flame\Database\Factories\HasFactory;
use Igniter\Flame\Database\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Spatie\WebhookServer\Events\WebhookCallEvent;
use Spatie\WebhookServer\Events\WebhookCallSucceededEvent;

/**
 * Webhook Log Model
 *
 * @property int $id
 * @property int $webhook_id
 * @property string $webhook_type
 * @property string $name
 * @property string $message
 * @property bool $is_success
 * @property array $payload
 * @property array $response
 * @property string $created_at
 * @property string $updated_at
 * @property string $event_code
 * @property string $exception
 */
class WebhookLog extends Model
{
    use HasFactory;
    use MassPrunable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'igniterlabs_webhook_logs';

    public $timestamps = true;

    /**
     * @var array Guarded fields
     */
    public $guarded = [];

    public $relation = [
        'morphTo' => [
            'webhook' => [Outgoing::class, 'name' => 'webhook'],
        ],
    ];

    protected $casts = [
        'webhook_id' => 'integer',
        'is_success' => 'boolean',
        'payload' => 'array',
        'response' => 'array',
    ];

    protected $appends = [
        'status_name', 'created_since',
    ];

    //
    //
    //
    /**
     * @param \Igniter\Flame\Database\Query\Builder $query
     * @param Model $webhook
     * @return mixed
     */
    public function scopeApplyWebhook($query, $webhook)
    {
        return $query
            ->where('webhook_type', $webhook->getMorphClass())
            ->where('webhook_id', $webhook->getKey());
    }

    //
    //
    //

    public static function createLog(WebhookCallEvent $eventPayload)
    {
        $response = [];
        if ($eventPayload->response instanceof Response) {
            $response = $eventPayload->response->getBody()->getContents();
        }

        $webhookSucceeded = $eventPayload instanceof WebhookCallSucceededEvent;

        $message = $webhookSucceeded
            ? 'Payload delivered successfully'
            : e($eventPayload->errorType ?? 'No error type available.')
            .':'.e($eventPayload->errorMessage ?? 'No error message available.');

        return self::query()->create([
            'webhook_id' => array_get($eventPayload->meta, 'webhook_id'),
            'webhook_type' => array_get($eventPayload->meta, 'webhook_type'),
            'name' => array_get($eventPayload->meta, 'name'),
            'event_code' => array_get($eventPayload->meta, 'event_code'),
            'payload' => $eventPayload->payload,
            'is_success' => $webhookSucceeded,
            'message' => $message,
            'response' => $response,
        ]);
    }

    //
    //
    //

    public function getStatusNameAttribute($value): string
    {
        return lang($this->is_success
            ? 'igniterlabs.webhook::default.text_success'
            : 'igniterlabs.webhook::default.text_failed',
        );
    }

    public function getCreatedSinceAttribute($value): ?string
    {
        return $this->created_at ? day_elapsed($this->created_at) : null;
    }

    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<=', now()->subMonth());
    }
}
