<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use AsyncPatternsLab\Fibers\FiberScheduler;
use AsyncPatternsLab\Fibers\FiberPool;

echo "=== PHP Fibers Example ===\n\n";

// Example 1: Basic Fiber Scheduler
echo "1. Basic Fiber Scheduler:\n";

$scheduler = new FiberScheduler();

$scheduler->schedule(function () {
    echo "   Task 1 executing...\n";
    return "Result 1";
}, 'task1');

$scheduler->schedule(function () {
    echo "   Task 2 executing...\n";
    return "Result 2";
}, 'task2');

$scheduler->schedule(function () {
    echo "   Task 3 executing...\n";
    return "Result 3";
}, 'task3');

$results = $scheduler->run();

echo "   Results: " . json_encode($results) . "\n\n";

// Example 2: Fiber Pool
echo "2. Fiber Pool (Concurrent Execution):\n";

$pool = new FiberPool(maxConcurrency: 3);

$tasks = [
    fn() => simulateFetch('https://api1.example.com'),
    fn() => simulateFetch('https://api2.example.com'),
    fn() => simulateFetch('https://api3.example.com'),
    fn() => simulateFetch('https://api4.example.com'),
    fn() => simulateFetch('https://api5.example.com'),
];

$startTime = microtime(true);
$results = $pool->map($tasks);
$duration = microtime(true) - $startTime;

echo "   Processed " . count($results) . " tasks in " . round($duration * 1000, 2) . "ms\n";
echo "   Results: " . json_encode($results) . "\n\n";

// Example 3: Real-world scenario - Parallel data processing
echo "3. Parallel Data Processing:\n";

$data = range(1, 10);

$pool = new FiberPool(maxConcurrency: 5);

$processingTasks = array_map(
    fn($item) => fn() => processDataItem($item),
    $data
);

$processedResults = $pool->map($processingTasks);

echo "   Processed " . count($processedResults) . " items\n";
echo "   Sample output: " . implode(', ', array_slice($processedResults, 0, 5)) . "...\n\n";

// Example 4: Error Handling
echo "4. Error Handling:\n";

$scheduler = new FiberScheduler();

$scheduler->schedule(fn() => "Success", 'success-task');

$scheduler->schedule(function () {
    throw new \RuntimeException("Task failed!");
}, 'failing-task');

try {
    $results = $scheduler->run();
    echo "   Results: " . json_encode($results) . "\n";
} catch (\Exception $e) {
    echo "   Caught exception: " . $e->getMessage() . "\n";
}

echo "\n=== Example Complete ===\n";

// Helper functions

function simulateFetch(string $url): array
{
    // Simulate network delay
    usleep(random_int(10000, 50000)); // 10-50ms
    
    return [
        'url' => $url,
        'status' => 200,
        'data' => 'Response from ' . $url,
        'time' => microtime(true),
    ];
}

function processDataItem(int $item): string
{
    // Simulate processing
    usleep(random_int(5000, 15000)); // 5-15ms
    
    return "Processed item #$item => " . ($item * 2);
}
