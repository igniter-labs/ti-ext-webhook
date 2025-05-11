<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Classes;

use Igniter\Flame\Database\Model;
use Igniter\Flame\Mail\Markdown;
use Igniter\Flame\Traits\ExtensionTrait;
use Illuminate\Support\Facades\File;

abstract class BaseAction
{
    use ExtensionTrait;

    protected string $path;

    /**
     * @var array Contains the event parameter values.
     */
    protected $params = [];

    /**
     * @param Model $model
     */
    public function __construct(
        /**
         * @var Model model object
         */
        protected $model = null,
    ) {
        $this->path = '$/'.strtolower(str_replace('\\', '/', static::class));
    }

    /**
     * Returns information about this action, including name and description.
     * @return array
     */
    public function actionDetails()
    {
        return [
            'name' => 'Webhook action',
            'description' => 'Webhook action description',
        ];
    }

    /**
     * Returns the action name.
     * @return array
     */
    public function actionName()
    {
        return array_get($this->actionDetails(), 'name', 'Action');
    }

    /**
     * Returns the action description.
     * @return string
     */
    public function actionDescription()
    {
        return array_get($this->actionDetails(), 'description', 'Action description');
    }

    /**
     * Sets multiple params.
     * @param array $params
     */
    public function setActionParams($params): void
    {
        $this->params = $params;
    }

    /**
     * Returns all params.
     * @return array
     */
    public function getActionParams()
    {
        return $this->params;
    }

    /**
     * Resolves an action identifier from the called class name or object.
     * @return string Identifier in format of vendor-extension-class
     */
    public function getActionIdentifier()
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

    /**
     * Registers a entry action with specific URL.
     */
    public function registerEntryPoints()
    {
        return [];
    }

    public function renderSetupPartial()
    {
        $setupPath = File::symbolizePath(sprintf('%s/%s', $this->path, 'setup.md'));
        if (!$setupPath = File::existsInsensitive($setupPath)) {
            return 'No setup instructions provided';
        }

        return Markdown::parseFile($setupPath)->toHtml();
    }

    public static function extend(callable $callback): void
    {
        self::extensionExtendCallback($callback);
    }
}
