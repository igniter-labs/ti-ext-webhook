<?php

namespace IgniterLabs\Webhook\Models;

use Igniter\Flame\Database\Model;

class Settings extends Model
{
    public array $implement = [\Igniter\System\Actions\SettingsModel::class];

    // A unique code
    public string $settingsCode = 'igniterlabs_webhook_settings';

    // Reference to field configuration
    public string $settingsFieldsConfig = 'settings';
}
