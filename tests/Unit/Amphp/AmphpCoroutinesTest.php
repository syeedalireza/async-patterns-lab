<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Tests\Unit\Amphp;

use AsyncPatternsLab\Amphp\AmphpCoroutines;
use PHPUnit\Framework\TestCase;

final class AmphpCoroutinesTest extends TestCase
{
    private AmphpCoroutines $coroutines;

    protected function setUp(): void
    {
        $this->coroutines = new AmphpCoroutines();
    }

    public function testParallelExecutesTasks(): void
    {
        $results = $this->coroutines->parallel([
            fn() => 1 + 1,
            fn() => 2 + 2,
            fn() => 3 + 3,
        ]);

        $this->assertCount(3, $results);
        $this->assertEquals([2, 4, 6], $results);
    }

    public function testBatchProcessHandlesItems(): void
    {
        $items = [1, 2, 3, 4, 5];
        
        $results = $this->coroutines->batchProcess(
            $items,
            fn($x) => $x * 2,
            batchSize: 2
        );

        $this->assertEquals([2, 4, 6, 8, 10], $results);
    }

    public function testWithTimeoutExecutesTask(): void
    {
        $result = $this->coroutines->withTimeout(fn() => 42, timeout: 1.0);
        $this->assertEquals(42, $result);
    }
}
