<?php

namespace IgniterLabs\Webhook\Models;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\WebhookConfig;

/**
 * Webhook Log Model
 */
class WebhookLog extends WebhookCall
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'igniterlabs_webhook_logs';

    /**
     * @var array Guarded fields
     */
    public $guarded = [];

    protected $casts = [
        'webhook_id' => 'integer',
        'is_success' => 'boolean',
        'payload' => 'array',
//        'request' => 'array',
        'response' => 'array',
        'exception' => 'array',
    ];

    public function webhook()
    {
        return $this->morphTo('webhook');
    }

    //
    //
    //

    public static function updateLog($event)
    {
    }

    /**
     * @param \Igniter\Flame\Database\Query\Builder $query
     * @param \Igniter\Flame\Database\Model $webhook
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

    public static function storeIncomingWebhook(Incoming $model, WebhookConfig $config, Request $request)
    {
        return self::create([
            'webhook_id' => $model->getKey(),
            'webhook_type' => $model->getMorphClass(),
            'name' => $config->name,
            'payload' => $request->input(),
        ]);
    }

    public static function storeWebhook(WebhookConfig $config, Request $request): WebhookCall
    {
//        return self::create([
//            'name' => $config->name,
//            'payload' => $request->input(),
//        ]);
    }

    public function markAsSuccessful()
    {
        $this->is_success = TRUE;

        $this->save();

        return $this;
    }

    public function markAsFailed()
    {
        $this->is_success = FALSE;

        $this->save();

        return $this;
    }
}