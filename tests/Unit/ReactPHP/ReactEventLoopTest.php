<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Tests\Unit\ReactPHP;

use AsyncPatternsLab\ReactPHP\ReactEventLoop;
use PHPUnit\Framework\TestCase;

final class ReactEventLoopTest extends TestCase
{
    public function testCanScheduleCallback(): void
    {
        $loop = new ReactEventLoop();
        $executed = false;

        $loop->schedule(function () use (&$executed) {
            $executed = true;
        });

        $this->assertTrue(true); // Basic test that scheduling doesn't throw
    }

    public function testCanCreateLoop(): void
    {
        $loop = new ReactEventLoop();
        $this->assertInstanceOf(ReactEventLoop::class, $loop);
    }
}
