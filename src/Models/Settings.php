<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Models;

use Igniter\Flame\Database\Model;
use Igniter\System\Actions\SettingsModel;

/**
 * @mixin SettingsModel
 */
class Settings extends Model
{
    public array $implement = [SettingsModel::class];

    // A unique code
    public string $settingsCode = 'igniterlabs_webhook_settings';

    // Reference to field configuration
    public string $settingsFieldsConfig = 'settings';

    public static function getHeaders(): array
    {
        $headers = self::get('headers');

        return is_array($headers) ? array_column($headers, 'value', 'key') : [];
    }
}
