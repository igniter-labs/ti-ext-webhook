<?php

//Route::any('admin/tester', function () {
//    Admin\Models\Customers_model::where('email', 'email@domain.tld')->delete();
//    Admin\Models\Customers_model::create(['email' => 'email@domain.tld']);
//});

Route::post('webhook/{action}/{hash}', [
    'as' => 'igniterlabs_webhook_incoming',
    'middleware' => ['web'],
    function ($action, $hash) {
        return \IgniterLabs\Webhook\Classes\WebhookManager::runEntryPoint($action, $hash);
    },
])->where('action', '[a-zA-Z-_]+')->where('hash', '[a-zA-Z0-9]+');
