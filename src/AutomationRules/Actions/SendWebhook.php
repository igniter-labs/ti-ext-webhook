<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\AutomationRules\Actions;

use Igniter\Automation\AutomationException;
use Igniter\Automation\Classes\BaseAction;
use Igniter\Automation\Models\RuleAction;
use Override;
use Spatie\WebhookServer\WebhookCall;

/**
 * SendWebhook Action
 *
 * @property RuleAction $model
 */
class SendWebhook extends BaseAction
{
    #[Override]
    public function actionDetails(): array
    {
        return [
            'name' => 'Send payload to Webhooks',
            'description' => "Send HTTP POST payload to the webhook's URL",
        ];
    }

    #[Override]
    public function defineFormFields(): array
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

    public function triggerAction($params): void
    {
        if (!$webhookUrl = $this->model->url) {
            throw new AutomationException('Send Webhook event rule is missing a valid webhook url');
        }

        $webhookJob = WebhookCall::create()->url($webhookUrl);

        $payload = array_except($params, ['order', 'reservation']);
        $webhookJob->payload(['action' => $this->model->automation_rule->code] + $payload);

        if (strlen((string)$webhookSecret = $this->model->secret) !== 0) {
            $webhookJob->useSecret($webhookSecret);
        } else {
            $webhookJob->doNotSign();
        }

        $webhookJob->meta([
            'webhook_id' => $this->model->automation_rule->getKey(),
            'webhook_type' => $this->model->automation_rule->getMorphClass(),
            'name' => $this->model->automation_rule->name,
            'event_code' => $this->model->automation_rule->code,
        ]);

        $webhookJob->dispatch();
    }
}
