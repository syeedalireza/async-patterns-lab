<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Common;

/**
 * Basic async task implementation
 */
final class AsyncTask implements AsyncTaskInterface
{
    public function __construct(
        private readonly string $id,
        private readonly \Closure $callback,
        private readonly int $priority = 0
    ) {
    }

    public function execute(): mixed
    {
        return ($this->callback)();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
