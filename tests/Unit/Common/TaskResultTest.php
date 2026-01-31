<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Tests\Unit\Common;

use AsyncPatternsLab\Common\TaskResult;
use PHPUnit\Framework\TestCase;

final class TaskResultTest extends TestCase
{
    public function testSuccessfulResult(): void
    {
        $result = new TaskResult('task-1', 42, null, 0.1);
        
        $this->assertEquals('task-1', $result->getTaskId());
        $this->assertEquals(42, $result->getValue());
        $this->assertNull($result->getError());
        $this->assertEquals(0.1, $result->getExecutionTime());
        $this->assertTrue($result->isSuccess());
        $this->assertFalse($result->isFailure());
    }

    public function testFailedResult(): void
    {
        $error = new \Exception('Task failed');
        $result = new TaskResult('task-2', null, $error, 0.2);
        
        $this->assertEquals('task-2', $result->getTaskId());
        $this->assertSame($error, $result->getError());
        $this->assertTrue($result->isFailure());
        $this->assertFalse($result->isSuccess());
    }
}
