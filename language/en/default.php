<?php

return [
    'text_title' => 'Webhooks',
    'text_tab_general' => 'General',
    'text_tab_setup' => 'Setup Instructions',
    'text_outgoing' => 'Webhooks',
    'text_incoming' => 'Incoming webhooks',

    'label_enable_authentication' => 'Enable Authentication',
    'label_server_signature_header' => 'Webhook Signature Header Name',
    'label_headers' => 'Additional HTTP Headers',
    'label_timeout_in_seconds' => 'Webhook Timeout',
    'label_tries' => 'Webhook Tries',
    'label_verify_ssl' => 'Verify SSL Certificate',
    'label_client_signature_header' => 'Incoming Webhook Signature Header Name',

    'help_enable_authentication' => 'Authenticate outgoing webhook requests that requires authentication.',
    'help_server_signature_header' => 'The HTTP header key name of where the signature will be added for outgoing requests.',
    'help_headers' => 'Add headers to be added to all outgoing webhook requests',
    'help_timeout_in_seconds' => 'The number of seconds it will take to send a webhook before giving up.',
    'help_tries' => 'The number of times to try sending a webhook before giving up.',
    'help_verify_ssl' => 'Verify whether the SSL certificate of the webhook destination is valid.',
    'help_client_signature_header' => 'The HTTP header key name of where the signature will be added for incoming requests.',

    'outgoing' => [
        'text_title' => 'Webhooks',
        'text_form_name' => 'Webhook',
        'text_tab_deliveries' => 'Recent Deliveries',
        'text_empty' => 'There are no webhooks available.',

        'label_url' => 'Payload Url',
        'label_content_type' => 'Content Type',
        'label_verify_ssl' => 'Verify SSL',
        'label_secret' => 'Secret Signature',
        'label_events' => 'Events',
        'label_events_setup' => 'Select an event to see the setup instructions',

        'column_url' => 'Payload Url',

        'help_url' => 'Specify the URL to receive the webhook POST requests.',
        'help_content_type' => 'Specify the content type to use when delivering payloads.',
        'help_secret' => 'Set a webhook secret to secure your webhook POST requests. When creating, leave blank to generate one automatically.',
        'help_verify_ssl' => 'Whether to verify SSL certificates when delivering payloads',
        'help_events' => 'Which events would you like to trigger this webhook?',
    ],

    'incoming' => [
        'text_title' => 'Incoming Webhooks',
        'text_form_name' => 'Incoming Webhook',
        'text_tab_calls' => 'Recent Calls',
        'text_empty' => 'There are no incoming webhooks available.',

        'label_url' => 'Webhook Url',
        'label_action' => 'Action',
        'label_secret' => 'Signing Secret',

        'column_url' => 'Webhook Url',

        'help_url' => 'Send the webhook action\'s JSON payloads to this URL.',
        'help_action' => 'Specify the action to perform when the webhook is triggered',
        'help_secret' => 'Setting this expects that every incoming webhook will be signed using this secret. It helps ensure payload has not been tampered with.',
    ],

    'automation' => [
        'label_webhooks' => 'Webhooks',
        'label_url' => 'Url',
        'label_signature' => 'Signature Key',

        'help_webhooks' => 'Webhooks allow you to set up integrations, which triggers when certain events occur within TastyIgniter. When an event is triggered, a HTTP POST payload is sent to the webhook\'s URL. Webhooks can be used to push new orders to your POS.',
        'help_url' => 'A POST request will be sent to the URL with details of the subscribed events. Data format will be JSON',
    ],
];