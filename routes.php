<?php

Route::post('webhook/{action}/{hash}', [
    'as' => 'igniterlabs_webhook_incoming',
    'middleware' => ['web'],
    function ($action, $hash) {
        return \IgniterLabs\Webhook\Classes\WebhookManager::runEntryPoint($action, $hash);
    },
])->where('action', '[a-zA-Z-_]+')->where('hash', '[a-zA-Z0-9]+');
