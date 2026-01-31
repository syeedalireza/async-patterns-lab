<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Benchmarks;

use AsyncPatternsLab\Fibers\FiberScheduler;

/**
 * Benchmark suite for comparing async patterns
 */
final class AsyncBenchmark
{
    /**
     * Benchmark Fiber-based execution
     *
     * @param int $iterations
     * @return array<string, mixed>
     */
    public function benchmarkFibers(int $iterations): array
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $scheduler = new FiberScheduler();

        for ($i = 0; $i < $iterations; $i++) {
            $scheduler->schedule(function () use ($i) {
                return $i * 2;
            }, "task_$i");
        }

        $results = $scheduler->run();

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        return [
            'pattern' => 'Fibers',
            'iterations' => $iterations,
            'time_seconds' => $endTime - $startTime,
            'memory_bytes' => $endMemory - $startMemory,
            'throughput_ops_per_sec' => $iterations / ($endTime - $startTime),
            'avg_time_per_op_ms' => (($endTime - $startTime) / $iterations) * 1000,
        ];
    }

    /**
     * Benchmark synchronous execution (baseline)
     *
     * @param int $iterations
     * @return array<string, mixed>
     */
    public function benchmarkSync(int $iterations): array
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $results = [];
        for ($i = 0; $i < $iterations; $i++) {
            $results["task_$i"] = $i * 2;
        }

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        return [
            'pattern' => 'Synchronous',
            'iterations' => $iterations,
            'time_seconds' => $endTime - $startTime,
            'memory_bytes' => $endMemory - $startMemory,
            'throughput_ops_per_sec' => $iterations / ($endTime - $startTime),
            'avg_time_per_op_ms' => (($endTime - $startTime) / $iterations) * 1000,
        ];
    }

    /**
     * Compare multiple patterns
     *
     * @param int $iterations
     * @return array<int, array<string, mixed>>
     */
    public function comparePatterns(int $iterations = 1000): array
    {
        return [
            $this->benchmarkSync($iterations),
            $this->benchmarkFibers($iterations),
        ];
    }

    /**
     * Generate performance report
     *
     * @param array<int, array<string, mixed>> $results
     * @return string
     */
    public function generateReport(array $results): string
    {
        $report = "# Async Patterns Performance Report\n\n";
        $report .= "| Pattern | Time (s) | Memory (MB) | Throughput (ops/s) |\n";
        $report .= "|---------|----------|-------------|--------------------|\n";

        foreach ($results as $result) {
            $report .= sprintf(
                "| %s | %.4f | %.2f | %.0f |\n",
                $result['pattern'],
                $result['time_seconds'],
                $result['memory_bytes'] / 1024 / 1024,
                $result['throughput_ops_per_sec']
            );
        }

        return $report;
    }
}
