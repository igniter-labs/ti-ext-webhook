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
                    'href' => 'igniterlabs/webhook/outgoing/create',
                ],
            ],
        ],
        'bulkActions' => [
            'status' => [
                'label' => 'lang:admin::lang.list.actions.label_status',
                'type' => 'dropdown',
                'class' => 'btn btn-light',
                'statusColumn' => 'is_active',
                'menuItems' => [
                    'enable' => [
                        'label' => 'lang:admin::lang.list.actions.label_enable',
                        'type' => 'button',
                        'class' => 'dropdown-item',
                    ],
                    'disable' => [
                        'label' => 'lang:admin::lang.list.actions.label_disable',
                        'type' => 'button',
                        'class' => 'dropdown-item text-danger',
                    ],
                ],
            ],
            'delete' => [
                'label' => 'lang:admin::lang.button_delete',
                'class' => 'btn btn-light text-danger',
                'data-request-confirm' => 'lang:admin::lang.alert_warning_confirm',
            ],
        ],
        'columns' => [
            'edit' => [
                'type' => 'button',
                'iconCssClass' => 'fa fa-pencil',
                'attributes' => [
                    'class' => 'btn btn-edit',
                    'href' => 'igniterlabs/webhook/outgoing/edit/{id}',
                ],
            ],
            'name' => [
                'label' => 'lang:admin::lang.label_name',
                'type' => 'text',
            ],
            'url' => [
                'label' => 'lang:igniterlabs.webhook::default.outgoing.column_url',
                'type' => 'text',
            ],
            'is_active' => [
                'label' => 'lang:admin::lang.label_status',
                'type' => 'switch',
            ],
            'id' => [
                'label' => 'lang:admin::lang.column_id',
                'invisible' => true,
            ],
        ],
    ],
    'form' => [
        'toolbar' => [
            'buttons' => [
                'back' => [
                    'label' => 'lang:admin::lang.button_icon_back',
                    'class' => 'btn btn-outline-secondary',
                    'href' => 'igniterlabs/webhook/outgoing',
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
        'tabs' => [
            'defaultTab' => 'lang:igniterlabs.webhook::default.text_tab_general',
            'fields' => [
                'name' => [
                    'label' => 'lang:admin::lang.label_name',
                    'type' => 'text',
                    'span' => 'left',
                ],
                'is_active' => [
                    'label' => 'lang:admin::lang.label_status',
                    'type' => 'switch',
                    'default' => true,
                    'span' => 'right',
                ],
                'url' => [
                    'label' => 'lang:igniterlabs.webhook::default.outgoing.label_url',
                    'type' => 'text',
                    'span' => 'left',
                    'comment' => 'lang:igniterlabs.webhook::default.outgoing.help_url',
                ],
                'config_data[secret_key]' => [
                    'label' => 'lang:igniterlabs.webhook::default.outgoing.label_secret',
                    'type' => 'text',
                    'span' => 'right',
                    'comment' => 'lang:igniterlabs.webhook::default.outgoing.help_secret',
                ],
                'config_data[content_type]' => [
                    'label' => 'lang:igniterlabs.webhook::default.outgoing.label_content_type',
                    'type' => 'select',
                    'options' => 'getContentTypeOptions',
                    'span' => 'left',
                    'comment' => 'lang:igniterlabs.webhook::default.outgoing.help_content_type',
                ],
                'config_data[verify_ssl]' => [
                    'label' => 'lang:igniterlabs.webhook::default.outgoing.label_verify_ssl',
                    'type' => 'switch',
                    'span' => 'right',
                    'default' => true,
                    'comment' => 'lang:igniterlabs.webhook::default.outgoing.help_verify_ssl',
                ],
                'events' => [
                    'label' => 'lang:igniterlabs.webhook::default.outgoing.label_events',
                    'type' => 'checkboxlist',
                    'commentAbove' => 'lang:igniterlabs.webhook::default.outgoing.help_events',
                ],

                'deliveries' => [
                    'tab' => 'lang:igniterlabs.webhook::default.outgoing.text_tab_deliveries',
                    'type' => 'datatable',
                    'context' => ['edit'],
                    'useAjax' => true,
                    'defaultSort' => ['created_at', 'desc'],
                    'columns' => [
                        'status_name' => [
                            'title' => 'lang:admin::lang.label_status',
                        ],
                        'event_code' => [
                            'title' => 'lang:igniterlabs.webhook::default.outgoing.label_event_code',
                        ],
                        'message' => [
                            'title' => 'lang:igniterlabs.webhook::default.outgoing.label_message',
                        ],
                        'created_since' => [
                            'title' => 'lang:admin::lang.column_date_added',
                        ],
                    ],
                ],

                'setup' => [
                    'tab' => 'lang:igniterlabs.webhook::default.text_tab_setup',
                    'label' => 'lang:igniterlabs.webhook::default.outgoing.label_events_setup',
                    'type' => 'setup',
                ],
            ],
        ],
    ],
];
