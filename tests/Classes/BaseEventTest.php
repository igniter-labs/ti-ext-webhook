<?php

declare(strict_types=1);

namespace IgniterLabs\Webhook\Tests\Classes;

use Igniter\Flame\Database\Model;
use IgniterLabs\Webhook\Classes\BaseEvent;
use IgniterLabs\Webhook\Models\Outgoing;
use Illuminate\Support\Facades\View;
use Mockery;

class TestEvent extends BaseEvent
{
    protected string $setupPartial = 'test.partial';
}

beforeEach(function(): void {
    $this->model = Mockery::mock(Model::class);
    $this->event = new TestEvent($this->model);
});

it('can get event details', function(): void {
    expect($this->event->eventDetails())->toBe([
        'name' => 'Webhook event',
        'description' => 'Webhook event description',
    ]);
});

it('can register event listeners', function(): void {
    expect(TestEvent::registerEventListeners())->toBe([]);
});

it('can make payload from event', function(): void {
    expect(TestEvent::makePayloadFromEvent(['test' => 'data'], 'created'))->toBe([]);
});

it('can get event name', function(): void {
    expect($this->event->eventName())->toBe('Webhook event');
});

it('can get event description', function(): void {
    expect($this->event->eventDescription())->toBe('Webhook event description');
});

it('can set and get event payload', function(): void {
    $payload = ['test' => 'data'];
    $this->event->setEventPayload($payload);

    expect($this->event->getEventPayload())->toBe($payload);
});

it('can get event identifier', function(): void {
    expect($this->event->getEventIdentifier())->toBe('igniterlabs-webhook-testevent');
});

it('returns default message when setup partial does not exist', function(): void {
    View::shouldReceive('exists')
        ->with('test.partial')
        ->andReturn(false);

    $html = $this->event->renderSetupPartial();

    expect($html)->toBe('No setup instructions provided');
});

it('can extend the class', function(): void {
    $called = false;

    TestEvent::extend(function($event) use (&$called): void {
        $called = true;
    });

    $outgoing = Outgoing::factory()->create();
    $outgoing->applyEventClass(TestEvent::class);

    expect($called)->toBeTrue();
});
