<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Tests\Unit\Common;

use AsyncPatternsLab\Common\AsyncTask;
use PHPUnit\Framework\TestCase;

final class AsyncTaskTest extends TestCase
{
    public function testTaskExecutesCallback(): void
    {
        $task = new AsyncTask('test-task', fn() => 42);
        
        $result = $task->execute();
        
        $this->assertEquals(42, $result);
    }

    public function testTaskHasId(): void
    {
        $task = new AsyncTask('my-task', fn() => null);
        
        $this->assertEquals('my-task', $task->getId());
    }

    public function testTaskHasPriority(): void
    {
        $task = new AsyncTask('task', fn() => null, priority: 5);
        
        $this->assertEquals(5, $task->getPriority());
    }

    public function testDefaultPriorityIsZero(): void
    {
        $task = new AsyncTask('task', fn() => null);
        
        $this->assertEquals(0, $task->getPriority());
    }
}
