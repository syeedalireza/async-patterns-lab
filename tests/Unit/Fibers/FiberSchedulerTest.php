<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Tests\Unit\Fibers;

use AsyncPatternsLab\Fibers\FiberScheduler;
use Fiber;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FiberScheduler::class)]
final class FiberSchedulerTest extends TestCase
{
    private FiberScheduler $scheduler;

    protected function setUp(): void
    {
        $this->scheduler = new FiberScheduler();
    }

    #[Test]
    public function it_schedules_and_runs_single_fiber(): void
    {
        $this->scheduler->schedule(fn () => 42, 'task1');

        $results = $this->scheduler->run();

        $this->assertSame(['task1' => 42], $results);
    }

    #[Test]
    public function it_runs_multiple_fibers(): void
    {
        $this->scheduler->schedule(fn () => 'result1', 'task1');
        $this->scheduler->schedule(fn () => 'result2', 'task2');
        $this->scheduler->schedule(fn () => 'result3', 'task3');

        $results = $this->scheduler->run();

        $this->assertCount(3, $results);
        $this->assertSame('result1', $results['task1']);
        $this->assertSame('result2', $results['task2']);
        $this->assertSame('result3', $results['task3']);
    }

    #[Test]
    public function it_handles_fiber_suspension(): void
    {
        $this->scheduler->schedule(function () {
            Fiber::suspend();

            return 'completed';
        }, 'suspended_task');

        $results = $this->scheduler->run();

        $this->assertSame('completed', $results['suspended_task']);
    }

    #[Test]
    public function it_throws_exception_for_duplicate_task_id(): void
    {
        $this->scheduler->schedule(fn () => 1, 'task1');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Task with ID 'task1' already scheduled");

        $this->scheduler->schedule(fn () => 2, 'task1');
    }

    #[Test]
    public function it_gets_result_for_specific_task(): void
    {
        $this->scheduler->schedule(fn () => 'value', 'task1');
        $this->scheduler->run();

        $result = $this->scheduler->getResult('task1');

        $this->assertSame('value', $result);
    }

    #[Test]
    public function it_clears_fibers_and_results(): void
    {
        $this->scheduler->schedule(fn () => 1, 'task1');
        $this->scheduler->run();

        $this->scheduler->clear();

        $this->assertNull($this->scheduler->getResult('task1'));
    }
}
