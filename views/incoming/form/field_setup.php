<?php
$actionObj = \IgniterLabs\Webhook\Classes\WebhookManager::instance()->getActionObject($formModel->action);
?>
<div
    class="card card-body bg-white markdown"
><?= $actionObj ? $actionObj->renderSetupPartial() : null; ?></div>