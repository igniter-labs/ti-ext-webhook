<?php

namespace IgniterLabs\Webhook\AutomationRules\Actions;

use Igniter\Automation\Classes\BaseAction;
use Igniter\Flame\Exception\ApplicationException;
use IgniterLabs\Webhook\Classes\WebhookCall;
use IgniterLabs\Webhook\Classes\WebhookManager;

class SendWebhook extends BaseAction
{
    public function actionDetails()
    {
        return [
            'name' => 'Send payload to Webhooks',
            'description' => 'Send HTTP POST payload to the webhook\'s URL',
        ];
    }

    public function defineFormFields()
    {
        return [
            'fields' => [
                'webhooks' => [
                    'label' => 'lang:igniterlabs.webhook::default.automation.label_webhooks',
                    'type' => 'section',
                    'comment' => 'lang:igniterlabs.webhook::default.automation.help_webhooks',
                ],
                'url' => [
                    'label' => 'lang:igniterlabs.webhook::default.automation.label_url',
                    'type' => 'text',
                    'comment' => 'lang:igniterlabs.webhook::default.automation.help_url',
                ],
                'signature' => [
                    'label' => 'lang:igniterlabs.webhook::default.automation.label_signature',
                    'type' => 'text',
                    'default' => str_random(),
                ],
            ],
        ];
    }

    public function triggerAction($params)
    {
        if (!strlen($webhookUrl = $this->model->url))
            throw new ApplicationException('Send Webhook event rule is missing a valid webhook url');

        WebhookManager::instance();

        $webhookJob = WebhookCall::create()->url($webhookUrl);

        $payload = array_except($params, ['order', 'reservation']);
        $webhookJob->payload(['action' => $this->model->automation_rule->code] + $payload);

        if (strlen($webhookSecret = $this->model->secret)) {
            $webhookJob->useSecret($webhookSecret);
        }
        else {
            $webhookJob->doNotSign();
        }

        $webhookJob->dispatch();
    }
}
