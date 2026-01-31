<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Tests\Unit\Benchmarks;

use AsyncPatternsLab\Benchmarks\AsyncBenchmark;
use PHPUnit\Framework\TestCase;

final class AsyncBenchmarkTest extends TestCase
{
    private AsyncBenchmark $benchmark;

    protected function setUp(): void
    {
        $this->benchmark = new AsyncBenchmark();
    }

    public function testBenchmarkFibersReturnsValidResults(): void
    {
        $results = $this->benchmark->benchmarkFibers(iterations: 10);

        $this->assertArrayHasKey('pattern', $results);
        $this->assertEquals('Fibers', $results['pattern']);
        $this->assertArrayHasKey('time_seconds', $results);
        $this->assertArrayHasKey('memory_bytes', $results);
        $this->assertArrayHasKey('throughput_ops_per_sec', $results);
        $this->assertGreaterThan(0, $results['throughput_ops_per_sec']);
    }

    public function testBenchmarkSyncReturnsValidResults(): void
    {
        $results = $this->benchmark->benchmarkSync(iterations: 10);

        $this->assertEquals('Synchronous', $results['pattern']);
        $this->assertGreaterThan(0, $results['time_seconds']);
    }

    public function testComparePatternsReturnsMultipleResults(): void
    {
        $results = $this->benchmark->comparePatterns(iterations: 10);

        $this->assertCount(2, $results);
        $this->assertEquals('Synchronous', $results[0]['pattern']);
        $this->assertEquals('Fibers', $results[1]['pattern']);
    }

    public function testGenerateReportCreatesMarkdown(): void
    {
        $results = $this->benchmark->comparePatterns(iterations: 10);
        $report = $this->benchmark->generateReport($results);

        $this->assertStringContainsString('Performance Report', $report);
        $this->assertStringContainsString('Synchronous', $report);
        $this->assertStringContainsString('Fibers', $report);
    }
}
