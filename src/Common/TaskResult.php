<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Common;

/**
 * Represents the result of an async task execution
 */
final class TaskResult
{
    public function __construct(
        private readonly string $taskId,
        private readonly mixed $value,
        private readonly ?\Throwable $error = null,
        private readonly float $executionTime = 0.0
    ) {
    }

    public function getTaskId(): string
    {
        return $this->taskId;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getError(): ?\Throwable
    {
        return $this->error;
    }

    public function getExecutionTime(): float
    {
        return $this->executionTime;
    }

    public function isSuccess(): bool
    {
        return $this->error === null;
    }

    public function isFailure(): bool
    {
        return $this->error !== null;
    }
}
