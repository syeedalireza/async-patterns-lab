<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use AsyncPatternsLab\Benchmarks\AsyncBenchmark;
use AsyncPatternsLab\Benchmarks\MemoryProfiler;

echo "=== Async Patterns Benchmark ===\n\n";

$benchmark = new AsyncBenchmark();

// Run benchmarks
echo "Running benchmarks (this may take a few seconds)...\n\n";

$iterations = 100;

// Benchmark 1: Synchronous
echo "1. Benchmarking Synchronous execution...\n";
$syncResults = $benchmark->benchmarkSync($iterations);
displayResults($syncResults);

echo "\n";

// Benchmark 2: Fibers
echo "2. Benchmarking PHP Fibers...\n";
$fiberResults = $benchmark->benchmarkFibers($iterations);
displayResults($fiberResults);

echo "\n";

// Comprehensive comparison
echo "=== Performance Comparison ===\n\n";
$allResults = [$syncResults, $fiberResults];
echo $benchmark->generateReport($allResults);

echo "\n";

// Memory profiling
echo "=== Memory Profiling ===\n\n";

$profiler = new MemoryProfiler();
$profiler->start();

echo "Allocating memory for test...\n";

// Simulate some work
$data = [];
for ($i = 0; $i < 1000; $i++) {
    $data[] = range(1, 100);
}

$profiler->snapshot('after_allocation');

unset($data);

$profiler->snapshot('after_cleanup');

echo $profiler->generateReport();

echo "\n=== Benchmark Complete ===\n";

// Helper function
function displayResults(array $results): void
{
    echo "   Pattern: {$results['pattern']}\n";
    echo "   Iterations: {$results['iterations']}\n";
    echo "   Time: " . round($results['time_seconds'], 4) . " seconds\n";
    echo "   Memory: " . round($results['memory_bytes'] / 1024, 2) . " KB\n";
    echo "   Throughput: " . round($results['throughput_ops_per_sec'], 0) . " ops/sec\n";
    echo "   Avg time per op: " . round($results['avg_time_per_op_ms'], 3) . " ms\n";
}
