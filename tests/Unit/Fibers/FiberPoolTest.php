<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Tests\Unit\Fibers;

use AsyncPatternsLab\Fibers\FiberPool;
use PHPUnit\Framework\TestCase;

final class FiberPoolTest extends TestCase
{
    public function testCanExecuteTasksInPool(): void
    {
        $pool = new FiberPool(maxConcurrency: 2);

        $results = $pool->map([
            fn() => 1 + 1,
            fn() => 2 + 2,
            fn() => 3 + 3,
        ]);

        $this->assertCount(3, $results);
        $this->assertEquals([2, 4, 6], $results);
    }

    public function testPoolRespectsMaxConcurrency(): void
    {
        $pool = new FiberPool(maxConcurrency: 2);
        $this->assertEquals(2, $pool->getMaxConcurrency());
    }

    public function testPoolHandlesEmptyTasks(): void
    {
        $pool = new FiberPool();
        $results = $pool->map([]);
        
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function testPoolExecutesSingleTask(): void
    {
        $pool = new FiberPool();
        $results = $pool->map([fn() => 'hello']);
        
        $this->assertEquals(['hello'], $results);
    }
}
