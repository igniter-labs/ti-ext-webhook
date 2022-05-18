<?php

namespace IgniterLabs\Webhook\Models;

use Igniter\Flame\Database\Model;

class Settings extends Model
{
    public $implement = [\Igniter\System\Actions\SettingsModel::class];

    // A unique code
    public $settingsCode = 'igniterlabs_webhook_settings';

    // Reference to field configuration
    public $settingsFieldsConfig = 'settings';
}
