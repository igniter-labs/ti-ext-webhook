<?php

namespace IgniterLabs\Webhook\Models;

use Exception;
use Igniter\Flame\Database\Model;
use IgniterLabs\Webhook\Classes\BaseAction;
use IgniterLabs\Webhook\Classes\WebhookClientProcessor;
use IgniterLabs\Webhook\Classes\WebhookManager;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Spatie\WebhookClient\WebhookConfig;
use System\Classes\ErrorHandler;

/**
 * Incoming Webhook Model
 */
class Incoming extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'igniterlabs_webhook_incoming';

    public $timestamps = TRUE;

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    protected $append = ['url'];

    protected $casts = [
        'is_active' => 'boolean',
        'config_data' => 'array',
    ];

    public $relation = [
        'morphMany' => [
            'calls' => ['IgniterLabs\Webhook\Models\WebhookLog', 'name' => 'webhook', 'delete' => TRUE],
        ],
    ];

    protected $actionClassName;

    /**
     * @param string $actionCode
     * @param string $hash
     * @return self
     */
    public static function findByCodeHash(string $actionCode, string $hash)
    {
        return self::where('action', $actionCode)->where('hash', $hash)->first();
    }

    public function getDropdownOptions()
    {
        return array_map(function (BaseAction $action) {
            return [$action->actionName(), $action->actionDescription()];
        }, WebhookManager::instance()->listActionObjects());
    }

    public function getUrlAttribute()
    {
        if (!$this->exists)
            return null;

        return URL::route('igniterlabs_webhook_incoming', [$this->action, $this->hash], TRUE);
    }

    public function processWebHook()
    {
        try {
            $request = app('request');
            $webhookConfig = app(WebhookConfig::class);
            $webhookConfig->signingSecret = array_get($this->config_data, 'signing_secret');

            $processor = new WebhookClientProcessor($request, $webhookConfig);

            $processor->setWebhook($this);

            return $processor->process();
        }
        catch (Exception $ex) {
            return response()->json(['message' => ErrorHandler::getDetailedMessage($ex)]);
        }
    }

    //
    // Events
    //

    protected function afterFetch()
    {
        $this->applyActionClass();
    }

    protected function beforeCreate()
    {
        $this->generateHash();

        $configData = $this->config_data ?? [];
        if (!array_get($configData, 'signing_secret')) {
            $configData['signing_secret'] = Str::random(16);
        }

        $this->config_data = $configData;
    }

    //
    // Manager
    //

    /**
     * Extends this class with the action class
     * @param string $className Class name
     * @return bool
     */
    public function applyActionClass($className = null)
    {
        if (is_null($className))
            $className = WebhookManager::instance()->getActionClass($this->action);

        if (!class_exists($className))
            $className = null;

        if ($className AND !$this->isClassExtendedWith($className)) {
            $this->extendClassWith($className);
        }

        $this->actionClassName = $className;

        return !is_null($className);
    }

    /**
     * Returns the action class extension object.
     * @return \IgniterLabs\Webhook\Classes\BaseEvent
     */
    public function getActionObject()
    {
        return $this->asExtension($this->actionClassName);
    }

    protected function generateHash()
    {
        do {
            $this->hash = Str::random(12);
        } while ($this->newQuery()->where('hash', $this->hash)->count() > 0);
    }
}
