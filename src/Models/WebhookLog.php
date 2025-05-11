<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Models;

use GuzzleHttp\Psr7\Response;
use Igniter\Flame\Database\Model;
use IgniterLabs\Webhook\Classes\EventPayload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;

/**
 * Webhook Log Model
 */
class WebhookLog extends Model
{
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

    protected $casts = [
        'webhook_id' => 'integer',
        'is_success' => 'boolean',
        'payload' => 'array',
        'response' => 'array',
    ];

    protected $appends = [
        'status_name', 'created_since',
    ];

    public function webhook()
    {
        return $this->morphTo('webhook');
    }

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

    public static function createLog(EventPayload $eventPayload, $isSuccess = false)
    {
        $response = [];
        if ($eventPayload->response instanceof Response) {
            $response = $eventPayload->response->getBody()->getContents();
        }

        $message = $isSuccess
            ? 'Payload delivered successfully'
            : e($eventPayload->errorMessage ?? 'No error message available.');

        return self::create(array_merge($eventPayload->meta, [
            'payload' => $eventPayload->payload,
            'is_success' => $isSuccess,
            'message' => $message,
            'response' => $response,
        ]));
    }

    public function markAsSuccessful(): static
    {
        $this->is_success = true;

        $this->save();

        return $this;
    }

    public function markAsFailed(): static
    {
        $this->is_success = false;

        $this->save();

        return $this;
    }

    //
    //
    //

    public function getStatusNameAttribute($value): string
    {
        return lang($this->is_success
            ? 'igniterlabs.webhook::default.text_success'
            : 'igniterlabs.webhook::default.text_failed'
        );
    }

    public function getCreatedSinceAttribute($value): ?string
    {
        return $this->created_at ? day_elapsed($this->created_at) : null;
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subMonth());
    }
}
