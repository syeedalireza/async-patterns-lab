<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Fibers;

use Fiber;
use InvalidArgumentException;

/**
 * Fiber pool for concurrent execution with limited concurrency.
 */
final class FiberPool
{
    /**
     * @var array<Fiber>
     */
    private array $activeFibers = [];

    /**
     * @param int $maxConcurrency Maximum number of concurrent fibers
     */
    public function __construct(
        private int $maxConcurrency = 10,
    ) {
        if ($maxConcurrency < 1) {
            throw new InvalidArgumentException('Max concurrency must be at least 1');
        }
    }

    /**
     * Execute multiple tasks concurrently with limited concurrency.
     *
     * @param array<callable> $tasks Array of callables to execute
     *
     * @return array<mixed> Results in same order as tasks
     */
    public function map(array $tasks): array
    {
        $results     = [];
        $taskQueue   = array_values($tasks);
        $totalTasks  = count($taskQueue);
        $currentTask = 0;

        while ($currentTask < $totalTasks || count($this->activeFibers) > 0) {
            // Start new fibers up to max concurrency
            while ($currentTask < $totalTasks && count($this->activeFibers) < $this->maxConcurrency) {
                $taskIndex                      = $currentTask;
                $fiber                          = new Fiber($taskQueue[$currentTask]);
                $this->activeFibers[$taskIndex] = $fiber;
                $fiber->start();
                $currentTask++;
            }

            // Process active fibers
            foreach ($this->activeFibers as $index => $fiber) {
                if ($fiber->isSuspended()) {
                    $fiber->resume();
                }

                if ($fiber->isTerminated()) {
                    $results[$index] = $fiber->getReturn();
                    unset($this->activeFibers[$index]);
                }
            }
        }

        // Sort results by original task index
        ksort($results);

        return array_values($results);
    }

    /**
     * Get current number of active fibers.
     */
    public function getActiveCount(): int
    {
        return count($this->activeFibers);
    }
}
