<?php

namespace IgniterLabs\Webhook\Traits;

use Igniter\Flame\Exception\ApplicationException;

trait ProcessWebhookActions
{
    public function processCreateAction($webhookCall, $entryPoint)
    {
        $this->createModel()->fill(
            $this->validateRequest()
        )->save();
    }

    public function processUpdateAction($webhookCall, $entryPoint)
    {
        $this->findModel($this->getRecordId($webhookCall))->fill(
            $this->validateRequest()
        )->save();
    }

    public function processDeleteAction($webhookCall, $entryPoint)
    {
        $this->findModel($this->getRecordId($webhookCall))->delete();
    }

    protected function createModel()
    {
        return new $this->modelClass;
    }

    protected function findModel($recordId)
    {
        if (!$model = $this->createModel()->find($recordId))
            throw new ApplicationException('Record not found');

        return $model;
    }

    protected function validateRequest()
    {
        return app()->make($this->requestClass)->validated();
    }
}
