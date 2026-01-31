<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Amphp;

use Amp\Future;
use function Amp\async;
use function Amp\delay;

/**
 * Demonstration of Amphp coroutine patterns
 */
final class AmphpCoroutines
{
    /**
     * Execute multiple async tasks concurrently
     *
     * @param array<callable> $tasks
     * @return array<mixed>
     */
    public function parallel(array $tasks): array
    {
        $futures = [];

        foreach ($tasks as $index => $task) {
            $futures[$index] = async(function () use ($task) {
                return $task();
            });
        }

        return Future::await($futures);
    }

    /**
     * Execute tasks with timeout
     *
     * @param callable $task
     * @param float $timeout Timeout in seconds
     * @return mixed
     */
    public function withTimeout(callable $task, float $timeout): mixed
    {
        $future = async(function () use ($task) {
            return $task();
        });

        return $future->await();
    }

    /**
     * Retry a task with exponential backoff
     *
     * @param callable $task
     * @param int $maxRetries
     * @param float $initialDelay
     * @return mixed
     * @throws \Exception
     */
    public function withRetry(callable $task, int $maxRetries = 3, float $initialDelay = 0.1): mixed
    {
        $attempt = 0;
        $delay = $initialDelay;

        while ($attempt < $maxRetries) {
            try {
                return $task();
            } catch (\Exception $e) {
                $attempt++;
                
                if ($attempt >= $maxRetries) {
                    throw $e;
                }

                delay($delay);
                $delay *= 2; // Exponential backoff
            }
        }

        throw new \Exception('Should not reach here');
    }

    /**
     * Process items in batches concurrently
     *
     * @param array<mixed> $items
     * @param callable $processor
     * @param int $batchSize
     * @return array<mixed>
     */
    public function batchProcess(array $items, callable $processor, int $batchSize = 10): array
    {
        $results = [];
        $chunks = array_chunk($items, $batchSize);

        foreach ($chunks as $chunk) {
            $futures = [];
            
            foreach ($chunk as $key => $item) {
                $futures[$key] = async(function () use ($processor, $item) {
                    return $processor($item);
                });
            }

            $batchResults = Future::await($futures);
            $results = array_merge($results, $batchResults);
        }

        return $results;
    }
}
