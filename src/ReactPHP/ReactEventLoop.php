<?php

declare(strict_types=1);

namespace AsyncPatternsLab\ReactPHP;

/**
 * ReactPHP Event Loop demonstration.
 * Shows event-driven async patterns with ReactPHP.
 *
 * Note: Actual implementation requires react/event-loop package.
 */
final class ReactEventLoop
{
    private array $timers = [];

    /**
     * Schedule a timer to execute after delay.
     *
     * @param float    $delay    Delay in seconds
     * @param callable $callback Callback to execute
     *
     * @return string Timer ID
     */
    public function addTimer(float $delay, callable $callback): string
    {
        $id                = uniqid('timer_', true);
        $this->timers[$id] = [
            'delay'    => $delay,
            'callback' => $callback,
            'time'     => microtime(true),
        ];

        return $id;
    }

    /**
     * Simulate event loop run.
     */
    public function run(): void
    {
        $currentTime = microtime(true);

        foreach ($this->timers as $id => $timer) {
            if ($currentTime - $timer['time'] >= $timer['delay']) {
                ($timer['callback'])();
                unset($this->timers[$id]);
            }
        }
    }
}
