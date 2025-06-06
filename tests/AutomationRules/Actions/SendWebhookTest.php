<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\AutomationRules\Actions;

use Igniter\Automation\AutomationException;
use Igniter\Automation\Models\AutomationRule;
use Igniter\Automation\Models\RuleAction;
use IgniterLabs\Webhook\AutomationRules\Actions\SendWebhook;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Spatie\WebhookServer\CallWebhookJob;

it('defines action details', function(): void {
    $details = (new SendWebhook)->actionDetails();

    expect($details)->toBeArray()
        ->and($details['name'])->toBe('Send payload to Webhooks')
        ->and($details['description'])->toBe("Send HTTP POST payload to the webhook's URL");
});

it('defines form fields', function(): void {
    $fields = (new SendWebhook)->defineFormFields();

    expect($fields)->toBeArray()
        ->and($fields['fields'])->toBeArray()
        ->toHaveKey('webhooks')
        ->toHaveKey('url')
        ->toHaveKey('signature');
});

it('throws an exception when no webhook URL is provided', function(): void {
    $model = Mockery::mock(RuleAction::class);
    $model->shouldReceive('extendableGet')
        ->with('url')
        ->andReturn(null);

    expect(fn() => (new SendWebhook($model))->triggerAction([]))
        ->toThrow(AutomationException::class, 'Send Webhook event rule is missing a valid webhook url');
});

it('dispatches a webhook with the correct payload', function(): void {
    Queue::fake();

    // Mock the model
    $rule = Mockery::mock(AutomationRule::class);
    $rule->shouldReceive('getKey')->andReturn(1);
    $rule->shouldReceive('getMorphClass')->andReturn('automation_rule');
    $rule->shouldReceive('extendableGet')->with('name')->andReturn('Test Rule');
    $rule->shouldReceive('extendableGet')->with('code')->andReturn('test_rule');

    $model = Mockery::mock(RuleAction::class);
    $model->shouldReceive('extendableGet')
        ->with('url')
        ->andReturn('https://example.com/webhook');
    $model->shouldReceive('extendableGet')
        ->with('secret')
        ->andReturn('secret');
    $model->shouldReceive('extendableGet')
        ->with('automation_rule')
        ->andReturn($rule);

    (new SendWebhook($model))->triggerAction(['test' => 'data']);

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) {
        return $job->payload['action'] === 'test_rule'
            && $job->payload['test'] === 'data'
            && $job->webhookUrl === 'https://example.com/webhook'
            && array_has($job->headers, ['Signature']);
    });
});

it('dispatches a webhook with the correct payload and no signature', function(): void {
    Queue::fake();

    // Mock the model
    $rule = Mockery::mock(AutomationRule::class);
    $rule->shouldReceive('getKey')->andReturn(1);
    $rule->shouldReceive('getMorphClass')->andReturn('automation_rule');
    $rule->shouldReceive('extendableGet')->with('name')->andReturn('Test Rule');
    $rule->shouldReceive('extendableGet')->with('code')->andReturn('test_rule');
    $model = Mockery::mock(RuleAction::class);
    $model->shouldReceive('extendableGet')
        ->with('url')
        ->andReturn('https://example.com/webhook');
    $model->shouldReceive('extendableGet')
        ->with('secret')
        ->andReturn(null);
    $model->shouldReceive('extendableGet')
        ->with('automation_rule')
        ->andReturn($rule);

    (new SendWebhook($model))->triggerAction(['test' => 'data']);

    Queue::assertPushed(CallWebhookJob::class, function(CallWebhookJob $job) {
        return $job->payload['action'] === 'test_rule'
            && $job->payload['test'] === 'data'
            && $job->webhookUrl === 'https://example.com/webhook'
            && !array_has($job->headers, ['Signature']);
    });
});
