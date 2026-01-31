<?php

declare(strict_types=1);

namespace AsyncPatternsLab\ReactPHP;

use React\Promise\Promise;
use React\Promise\PromiseInterface;
use function React\Promise\all;
use function React\Promise\resolve;
use function React\Promise\reject;

/**
 * Demonstration of ReactPHP promise-based patterns
 */
final class ReactPromises
{
    /**
     * Create a resolved promise
     *
     * @param mixed $value
     * @return PromiseInterface
     */
    public function createResolved(mixed $value): PromiseInterface
    {
        return resolve($value);
    }

    /**
     * Create a rejected promise
     *
     * @param \Throwable $reason
     * @return PromiseInterface
     */
    public function createRejected(\Throwable $reason): PromiseInterface
    {
        return reject($reason);
    }

    /**
     * Execute multiple promises in parallel
     *
     * @param array<PromiseInterface> $promises
     * @return PromiseInterface
     */
    public function all(array $promises): PromiseInterface
    {
        return all($promises);
    }

    /**
     * Chain promise operations
     *
     * @param PromiseInterface $promise
     * @param callable $onFulfilled
     * @param callable|null $onRejected
     * @return PromiseInterface
     */
    public function chain(
        PromiseInterface $promise,
        callable $onFulfilled,
        ?callable $onRejected = null
    ): PromiseInterface {
        return $promise->then($onFulfilled, $onRejected);
    }

    /**
     * Create a delayed promise
     *
     * @param mixed $value
     * @param float $delay Delay in seconds
     * @return PromiseInterface
     */
    public function delayed(mixed $value, float $delay): PromiseInterface
    {
        return new Promise(function ($resolve) use ($value, $delay) {
            $loop = \React\EventLoop\Loop::get();
            $loop->addTimer($delay, function () use ($resolve, $value) {
                $resolve($value);
            });
        });
    }

    /**
     * Race multiple promises (returns first completed)
     *
     * @param array<PromiseInterface> $promises
     * @return PromiseInterface
     */
    public function race(array $promises): PromiseInterface
    {
        return new Promise(function ($resolve, $reject) use ($promises) {
            foreach ($promises as $promise) {
                $promise->then($resolve, $reject);
            }
        });
    }

    /**
     * Retry a promise operation
     *
     * @param callable $operation Returns PromiseInterface
     * @param int $maxRetries
     * @return PromiseInterface
     */
    public function retry(callable $operation, int $maxRetries = 3): PromiseInterface
    {
        $attempt = function ($remaining) use ($operation, &$attempt): PromiseInterface {
            $promise = $operation();
            
            return $promise->then(
                null,
                function ($error) use ($remaining, $attempt) {
                    if ($remaining <= 0) {
                        return reject($error);
                    }
                    
                    return $attempt($remaining - 1);
                }
            );
        };

        return $attempt($maxRetries);
    }
}
