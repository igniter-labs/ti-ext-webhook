<?php

namespace IgniterLabs\Webhook\Classes;

use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

abstract class BaseEvent
{
    use \Igniter\Flame\Traits\ExtensionTrait;

    /**
     * @var \Igniter\Flame\Database\Model model object
     */
    protected $model;

    protected $setupPartial;

    /**
     * @var array Contains the event payload.
     */
    protected $payload = [];

    public function __construct($model = null)
    {
        $this->model = $model;
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
    public static function eventName()
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
        if (view()->exists($this->setupPartial)) {
            return Markdown::parse(File::get(View::make($this->setupPartial)->getPath()))->toHtml();
        }

        return 'No setup instructions provided';
    }

    public static function extend(callable $callback)
    {
        self::extensionExtendCallback($callback);
    }
}
