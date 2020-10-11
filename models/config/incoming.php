<?php

/**
 * Model configuration options for settings model.
 */

return [
    'list' => [
        'toolbar' => [
            'buttons' => [
                'create' => [
                    'label' => 'lang:admin::lang.button_new',
                    'class' => 'btn btn-primary',
                    'href' => 'igniterlabs/webhook/incoming/create',
                ],
                'delete' => [
                    'label' => 'lang:admin::lang.button_delete',
                    'class' => 'btn btn-danger',
                    'data-request' => 'onDelete',
                    'data-request-form' => '#list-form',
                    'data-request-confirm' => 'lang:admin::lang.alert_warning_confirm',
                ],
                'outgoing' => [
                    'label' => 'lang:igniterlabs.webhook::default.text_outgoing',
                    'class' => 'btn btn-default',
                    'href' => 'igniterlabs/webhook/outgoing',
                ],
            ],
        ],
        'columns' => [
            'edit' => [
                'type' => 'button',
                'iconCssClass' => 'fa fa-pencil',
                'attributes' => [
                    'class' => 'btn btn-edit',
                    'href' => 'igniterlabs/webhook/incoming/edit/{id}',
                ],
            ],
            'name' => [
                'label' => 'lang:admin::lang.label_name',
                'type' => 'text',
            ],
            'url' => [
                'label' => 'lang:igniterlabs.webhook::default.incoming.column_url',
                'type' => 'text',
            ],
            'is_active' => [
                'label' => 'lang:admin::lang.label_status',
                'type' => 'switch',
            ],
            'id' => [
                'label' => 'lang:admin::lang.column_id',
                'invisible' => TRUE,
            ],
        ],
    ],
    'form' => [
        'toolbar' => [
            'buttons' => [
                'back' => [
                    'label' => 'lang:admin::lang.button_icon_back',
                    'class' => 'btn btn-default',
                    'href' => 'igniterlabs/webhook/incoming',
                ],
                'save' => ['label' => 'lang:admin::lang.button_save', 'class' => 'btn btn-primary', 'data-request' => 'onSave'],
                'saveClose' => [
                    'label' => 'lang:admin::lang.button_save_close',
                    'class' => 'btn btn-default',
                    'data-request' => 'onSave',
                    'data-request-data' => 'close:1',
                ],
                'delete' => [
                    'label' => 'lang:admin::lang.button_icon_delete',
                    'class' => 'btn btn-danger',
                    'data-request' => 'onDelete',
                    'data-request-data' => "_method:'DELETE'",
                    'data-request-confirm' => 'lang:admin::lang.alert_warning_confirm',
                    'data-progress-indicator' => 'admin::lang.text_deleting',
                    'context' => ['edit'],
                ],
            ],
        ],
        'fields' => [
            'action' => [
                'label' => 'lang:igniterlabs.webhook::default.incoming.label_action',
                'type' => 'select',
                'span' => 'left',
                'comment' => 'lang:igniterlabs.webhook::default.incoming.help_action',
            ],
            'name' => [
                'label' => 'lang:admin::lang.label_name',
                'type' => 'text',
                'span' => 'right',
                'cssClass' => 'flex-width',
            ],
            'is_active' => [
                'label' => 'lang:admin::lang.label_status',
                'type' => 'switch',
                'default' => TRUE,
                'span' => 'right',
                'cssClass' => 'flex-width',
            ],
            'url' => [
                'label' => 'lang:igniterlabs.webhook::default.incoming.label_url',
                'type' => 'text',
                'span' => 'left',
                'disabled' => TRUE,
                'context' => ['edit'],
                'comment' => 'lang:igniterlabs.webhook::default.incoming.help_url',
            ],
            'config_data[signing_secret]' => [
                'label' => 'igniterlabs.webhook::default.incoming.label_secret',
                'type' => 'text',
                'span' => 'right',
                'disabled' => TRUE,
                'context' => ['edit'],
                'comment' => 'igniterlabs.webhook::default.incoming.help_secret',
            ],
        ],
        'tabs' => [
            'fields' => [
                'calls' => [
                    'tab' => 'lang:igniterlabs.webhook::default.incoming.text_tab_calls',
                    'type' => 'datatable',
                    'valueFrom' => 'calls',
                    'context' => ['edit'],
                    'columns' => [
                        'id' => [
                            'title' => 'lang:admin::lang.column_id',
                        ],
                        'is_success' => [
                            'title' => 'lang:admin::lang.label_status',
                        ],
                    ],
                ],
                'setup' => [
                    'tab' => 'lang:igniterlabs.webhook::default.text_tab_setup',
                    'type' => 'setup',
                    'context' => ['edit'],
                ],
            ],
        ],
    ],
];
