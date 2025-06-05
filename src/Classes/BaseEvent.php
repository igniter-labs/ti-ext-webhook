<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Classes;

use Igniter\Flame\Database\Model;
use Igniter\Flame\Traits\ExtensionTrait;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

abstract class BaseEvent
{
    use ExtensionTrait;

    protected string $setupPartial;

    /**
     * @var array Contains the event payload.
     */
    protected array $payload = [];

    /**
     * @param Model $model
     */
    public function __construct(
        /**
         * @var Model model object
         */
        protected $model = null,
    ) {}

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
     */
    public function setEventPayload(array $payload): void
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
     * @return string Identifier in format of vendor-extension-class
     */
    public function getEventIdentifier()
    {
        $namespace = normalize_class_name(static::class);
        if (strpos($namespace, '\\') === null) {
            return $namespace;
        }

        $parts = explode('\\', $namespace);
        $class = array_pop($parts);
        $slice = array_slice($parts, 1, 2);

        return strtolower(implode('-', $slice).'-'.$class);
    }

    public function renderSetupPartial()
    {
        if (view()->exists($this->setupPartial)) {
            return Markdown::parse(File::get(View::make($this->setupPartial)->getPath()))->toHtml();
        }

        return 'No setup instructions provided';
    }

    public static function extend(callable $callback): void
    {
        self::extensionExtendCallback($callback);
    }
}
