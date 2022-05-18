<?php

namespace IgniterLabs\Webhook\Classes;

use Igniter\Flame\Mail\Markdown;
use Igniter\Flame\Support\Facades\File;

abstract class BaseEvent
{
    use \Igniter\Flame\Traits\ExtensionTrait;

    /**
     * @var \Igniter\Flame\Database\Model model object
     */
    protected $model;

    protected $path;

    /**
     * @var array Contains the event payload.
     */
    protected $payload = [];

    public function __construct($model = null)
    {
        $this->model = $model;
        $this->path = '$/'.strtolower(str_replace('\\', '/', get_called_class()));
    }

    /**
     * Returns information about this event, including name and description.
     * @return array
     */
    public function eventDetails()
    {
        return [
            'name' => 'Webhook event',
            'description' => 'Webhook event description',
        ];
    }

    public static function registerEventListeners()
    {
        return [];
    }

    /**
     * Generates event payload based on arguments from the triggering system event.
     * @param array $args
     * @param string $actionCode
     * @return array
     */
    public static function makePayloadFromEvent(array $args, $actionCode = null)
    {
        return [];
    }

    /**
     * Returns the event name.
     * @return array
     */
    public function eventName()
    {
        return array_get($this->eventDetails(), 'name', 'Event');
    }

    /**
     * Returns the event description.
     * @return string
     */
    public function eventDescription()
    {
        return array_get($this->eventDetails(), 'description', 'Event description');
    }

    /**
     * Sets multiple payload.
     * @param array $payload
     * @return void
     */
    public function setEventPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Returns all payload.
     * @return array
     */
    public function getEventPayload()
    {
        return $this->payload;
    }

    /**
     * Resolves an event identifier from the called class name or object.
     * @param mixed Class name or object
     * @return string Identifier in format of vendor-extension-class
     */
    public function getEventIdentifier()
    {
        $namespace = normalize_class_name(get_called_class());
        if (strpos($namespace, '\\') === null) {
            return $namespace;
        }

        $parts = explode('\\', $namespace);
        $class = array_pop($parts);
        $slice = array_slice($parts, 1, 2);
        $code = strtolower(implode('-', $slice).'-'.$class);

        return $code;
    }

    public function renderSetupPartial()
    {
        $setupPath = File::symbolizePath(sprintf('%s/%s', $this->path, 'setup.md'));
        if (!$setupPath = File::existsInsensitive($setupPath))
            return 'No setup instructions provided';

        return Markdown::parseFile($setupPath)->toHtml();
    }

    public static function extend(callable $callback)
    {
        self::extensionExtendCallback($callback);
    }
}
