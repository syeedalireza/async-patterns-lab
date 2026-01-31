<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Tests\Unit\Benchmarks;

use AsyncPatternsLab\Benchmarks\MemoryProfiler;
use PHPUnit\Framework\TestCase;

final class MemoryProfilerTest extends TestCase
{
    public function testProfilerTracksMemory(): void
    {
        $profiler = new MemoryProfiler();
        $profiler->start();
        
        // Allocate some memory
        $data = range(1, 1000);
        
        $profiler->snapshot('after_allocation');
        $results = $profiler->getResults();

        $this->assertArrayHasKey('start_memory_bytes', $results);
        $this->assertArrayHasKey('current_memory_bytes', $results);
        $this->assertArrayHasKey('snapshots', $results);
        $this->assertCount(1, $results['snapshots']);
    }

    public function testFormatBytesConvertsCorrectly(): void
    {
        $profiler = new MemoryProfiler();

        $this->assertEquals('0 B', $profiler->formatBytes(0));
        $this->assertEquals('1 KB', $profiler->formatBytes(1024));
        $this->assertEquals('1 MB', $profiler->formatBytes(1024 * 1024));
    }

    public function testGenerateReportCreatesOutput(): void
    {
        $profiler = new MemoryProfiler();
        $profiler->start();
        $profiler->snapshot('test');
        
        $report = $profiler->generateReport();

        $this->assertStringContainsString('Memory Profiling Report', $report);
        $this->assertStringContainsString('Start:', $report);
        $this->assertStringContainsString('test', $report);
    }
}
