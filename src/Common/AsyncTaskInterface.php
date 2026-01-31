<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Common;

/**
 * Common interface for async tasks
 */
interface AsyncTaskInterface
{
    /**
     * Execute the task
     *
     * @return mixed
     */
    public function execute(): mixed;

    /**
     * Get task identifier
     */
    public function getId(): string;

    /**
     * Get task priority (higher = more important)
     */
    public function getPriority(): int;
}
