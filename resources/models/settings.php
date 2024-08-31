<?php

/**
 * Model configuration options for settings model.
 */

return [
    'form' => [
        'toolbar' => [
            'buttons' => [
                'save' => [
                    'label' => 'lang:admin::lang.button_save',
                    'class' => 'btn btn-primary',
                    'data-request' => 'onSave',
                    'data-progress-indicator' => 'admin::lang.text_saving',
                ],
            ],
        ],
        'fields' => [
            'enable_authentication' => [
                'tab' => 'igniterlabs.webhook::default.text_tab_server',
                'label' => 'igniterlabs.webhook::default.label_enable_authentication',
                'type' => 'switch',
                'default' => true,
                'span' => 'left',
                'comment' => 'igniterlabs.webhook::default.help_enable_authentication',
            ],
            'verify_ssl' => [
                'tab' => 'igniterlabs.webhook::default.text_tab_server',
                'label' => 'igniterlabs.webhook::default.label_verify_ssl',
                'type' => 'switch',
                'default' => true,
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
    ],
];
