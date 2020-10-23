<?php

/**
 * Model configuration options for settings model.
 */

return [
    'form' => [
        'toolbar' => [
            'buttons' => [
                'save' => ['label' => 'lang:admin::lang.button_save', 'class' => 'btn btn-primary', 'data-request' => 'onSave'],
                'saveClose' => [
                    'label' => 'lang:admin::lang.button_save_close',
                    'class' => 'btn btn-default',
                    'data-request' => 'onSave',
                    'data-request-data' => 'close:1',
                ],
            ],
        ],
        'fields' => [
            'enable_authentication' => [
                'tab' => 'igniterlabs.webhook::default.text_tab_server',
                'label' => 'igniterlabs.webhook::default.label_enable_authentication',
                'type' => 'switch',
                'default' => TRUE,
                'span' => 'left',
                'comment' => 'igniterlabs.webhook::default.help_enable_authentication',
            ],
            'verify_ssl' => [
                'tab' => 'igniterlabs.webhook::default.text_tab_server',
                'label' => 'igniterlabs.webhook::default.label_verify_ssl',
                'type' => 'switch',
                'default' => TRUE,
                'span' => 'right',
                'comment' => 'igniterlabs.webhook::default.help_verify_ssl',
            ],
            'timeout_in_seconds' => [
                'tab' => 'igniterlabs.webhook::default.text_tab_server',
                'label' => 'igniterlabs.webhook::default.label_timeout_in_seconds',
                'type' => 'number',
                'default' => 3,
                'span' => 'left',
                'comment' => 'igniterlabs.webhook::default.help_timeout_in_seconds',
            ],
            'tries' => [
                'tab' => 'igniterlabs.webhook::default.text_tab_server',
                'label' => 'igniterlabs.webhook::default.label_tries',
                'type' => 'number',
                'default' => 3,
                'span' => 'right',
                'comment' => 'igniterlabs.webhook::default.help_tries',
            ],
            'server_signature_header' => [
                'tab' => 'igniterlabs.webhook::default.text_tab_server',
                'label' => 'igniterlabs.webhook::default.label_server_signature_header',
                'type' => 'text',
                'default' => 'TI-Signature',
                'span' => 'left',
                'comment' => 'igniterlabs.webhook::default.help_server_signature_header',
            ],
            'client_signature_header' => [
                'tab' => 'igniterlabs.webhook::default.text_tab_server',
                'label' => 'igniterlabs.webhook::default.label_client_signature_header',
                'type' => 'text',
                'default' => 'Signature',
                'span' => 'right',
                'comment' => 'igniterlabs.webhook::default.help_client_signature_header',
            ],
            'headers' => [
                'tab' => 'igniterlabs.webhook::default.text_tab_server',
                'label' => 'igniterlabs.webhook::default.label_headers',
                'type' => 'repeater',
                'commentAbove' => 'igniterlabs.webhook::default.help_headers',
                'form' => [
                    'fields' => [
                        'key' => [
                            'label' => 'Key',
                            'type' => 'text',
                        ],
                        'value' => [
                            'label' => 'Value',
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ],
        'rules' => [
            ['enable_authentication', 'igniterlabs.webhook::default.label_enable_authentication', 'required|integer'],
            ['verify_ssl', 'igniterlabs.webhook::default.label_verify_ssl', 'required|integer'],
            ['timeout_in_seconds', 'igniterlabs.webhook::default.label_timeout_in_seconds', 'required|integer'],
            ['tries', 'igniterlabs.webhook::default.label_tries', 'required|integer'],
            ['server_signature_header', 'igniterlabs.webhook::default.label_server_signature_header', 'required|string'],
            ['client_signature_header', 'igniterlabs.webhook::default.label_client_signature_header', 'required|string'],
            ['headers', 'igniterlabs.webhook::default.label_headers', 'required|array'],
        ],
    ],
];
