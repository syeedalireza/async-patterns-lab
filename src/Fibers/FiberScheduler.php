<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Fibers;

use Fiber;
use InvalidArgumentException;

/**
 * Fiber-based task scheduler using PHP 8.2+ Fibers.
 * Demonstrates cooperative multitasking without blocking.
 */
final class FiberScheduler
{
    /**
     * @var array<Fiber>
     */
    private array $fibers = [];

    /**
     * @var array<string, mixed>
     */
    private array $results = [];

    /**
     * Schedule a task to run as a Fiber.
     *
     * @param callable $task Task to execute
     * @param string   $id   Unique task identifier
     */
    public function schedule(callable $task, string $id): void
    {
        if (isset($this->fibers[$id])) {
            throw new InvalidArgumentException("Task with ID '{$id}' already scheduled");
        }

        $this->fibers[$id] = new Fiber($task);
    }

    /**
     * Run all scheduled fibers cooperatively.
     *
     * @return array<string, mixed> Results indexed by task ID
     */
    public function run(): array
    {
        $running = $this->fibers;

        while (count($running) > 0) {
            foreach ($running as $id => $fiber) {
                if (! $fiber->isStarted()) {
                    $fiber->start();
                } elseif ($fiber->isSuspended()) {
                    $fiber->resume();
                }

                if ($fiber->isTerminated()) {
                    $this->results[$id] = $fiber->getReturn();
                    unset($running[$id]);
                }
            }
        }

        return $this->results;
    }

    /**
     * Get result for a specific task.
     */
    public function getResult(string $id): mixed
    {
        return $this->results[$id] ?? null;
    }

    /**
     * Clear all fibers and results.
     */
    public function clear(): void
    {
        $this->fibers  = [];
        $this->results = [];
    }
}
