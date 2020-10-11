<?php

namespace IgniterLabs\Webhook\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System\Actions\SettingsModel'];

    // A unique code
    public $settingsCode = 'igniterlabs_webhook_settings';

    // Reference to field configuration
    public $settingsFieldsConfig = 'settings';
}
