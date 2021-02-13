<?php

namespace IgniterLabs\Webhook\Classes;

use IgniterLabs\Webhook\Models\Incoming;
use Spatie\WebhookClient\Exceptions\WebhookFailed;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\WebhookProcessor;

class WebhookClientProcessor extends WebhookProcessor
{
    /**
     * @var \IgniterLabs\Webhook\Models\Incoming|\IgniterLabs\Webhook\Classes\BaseAction
     */
    protected $webhook;

    public function setWebhook(Incoming $webhook)
    {
        $this->webhook = $webhook;
    }

    public function process()
    {
        $this->ensureValidEntryPoint();

        $this->ensureValidSignature();

        if (!$this->config->webhookProfile->shouldProcess($this->request)) {
            return $this->createResponse();
        }

        $webhookCall = $this->storeWebhook();

        $this->processWebhook($webhookCall);

        return $this->createResponse();
    }

    protected function ensureValidEntryPoint()
    {
        if (!strlen($entryPoint = $this->request->input('action')))
            throw new WebhookFailed('The action parameter is missing.');

        if (!array_get($this->webhook->registerEntryPoints(), $entryPoint))
            throw new WebhookFailed(sprintf('The action [%s] is not a registered entry point.', $entryPoint));
    }

    protected function storeWebhook(): WebhookCall
    {
        return $this->config->webhookModel::createIncomingLog(
            $this->webhook, $this->config, $this->request
        );
    }
}
