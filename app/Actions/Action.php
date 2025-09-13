<?php

namespace App\Actions;

use Illuminate\Support\Facades\Event;

abstract class Action
{
    /**
     * Events to dispatch before the action runs
     */
    protected array $beforeEvents = [];

    /**
     * Events to dispatch after the action completes
     */
    protected array $afterEvents = [];

    /**
     * Handle the action execution
     *
     * @return mixed
     */
    abstract public function handle();

    /**
     * Execute the action with events
     */
    public function execute(...$args)
    {
        $this->dispatchBeforeEvents(...$args);

        $result = $this->handle(...$args);

        $this->dispatchAfterEvents($result, ...$args);

        return $result;
    }

    /**
     * Dispatch before events
     */
    protected function dispatchBeforeEvents(...$args): void
    {
        foreach ($this->beforeEvents as $event) {
            Event::dispatch(new $event(...$args));
        }
    }

    /**
     * Dispatch after events
     */
    protected function dispatchAfterEvents($result, ...$args): void
    {
        foreach ($this->afterEvents as $event) {
            Event::dispatch(new $event($result, ...$args));
        }
    }

    /**
     * Set events to dispatch
     */
    public function withEvents(array $before = [], array $after = []): self
    {
        $this->beforeEvents = $before;
        $this->afterEvents = $after;

        return $this;
    }

    /**
     * Create a fake instance for testing
     */
    public static function fake(): FakeAction
    {
        return app()->instance(static::class, new FakeAction(static::class));
    }

    /**
     * Partially mock specific methods for testing
     */
    public static function partialFake(array $methods = []): static
    {
        $mock = \Mockery::mock(static::class)->makePartial();

        foreach ($methods as $method => $return) {
            $mock->shouldReceive($method)->andReturn($return);
        }

        return app()->instance(static::class, $mock);
    }
}
