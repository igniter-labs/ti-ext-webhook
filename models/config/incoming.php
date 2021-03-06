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
                'save' => [
                    'label' => 'lang:admin::lang.button_save',
                    'context' => ['create', 'edit'],
                    'partial' => 'form/toolbar_save_button',
                    'class' => 'btn btn-primary',
                    'data-request' => 'onSave',
                    'data-progress-indicator' => 'admin::lang.text_saving',
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
                    'useAjax' => TRUE,
                    'defaultSort' => ['created_at', 'desc'],
                    'columns' => [
                        'created_since' => [
                            'title' => 'lang:admin::lang.column_date_added',
                        ],
                        'status_name' => [
                            'title' => 'lang:admin::lang.label_status',
                        ],
                        'message' => [
                            'title' => 'lang:igniterlabs.webhook::default.outgoing.label_message',
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
