<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use AsyncPatternsLab\Amphp\AmphpCoroutines;
use function Amp\delay;

echo "=== Amphp Coroutines Example ===\n\n";

$coroutines = new AmphpCoroutines();

// Example 1: Parallel Execution
echo "1. Parallel Task Execution:\n";

$results = $coroutines->parallel([
    function () {
        delay(0.1);
        return "Task 1 complete";
    },
    function () {
        delay(0.1);
        return "Task 2 complete";
    },
    function () {
        delay(0.1);
        return "Task 3 complete";
    },
]);

foreach ($results as $i => $result) {
    echo "   Result $i: $result\n";
}

echo "\n";

// Example 2: Batch Processing
echo "2. Batch Processing:\n";

$items = range(1, 20);

$results = $coroutines->batchProcess(
    $items,
    function ($item) {
        delay(0.01);
        return $item * 2;
    },
    batchSize: 5
);

echo "   Processed " . count($results) . " items\n";
echo "   First 5 results: " . implode(', ', array_slice($results, 0, 5)) . "\n\n";

// Example 3: Timeout Handling
echo "3. Task with Timeout:\n";

try {
    $result = $coroutines->withTimeout(
        function () {
            delay(0.5);
            return "Quick task completed";
        },
        timeout: 1.0
    );
    echo "   Result: $result\n";
} catch (\Exception $e) {
    echo "   Timeout: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 4: Retry Logic
echo "4. Retry with Exponential Backoff:\n";

$attempt = 0;
try {
    $result = $coroutines->withRetry(
        function () use (&$attempt) {
            $attempt++;
            echo "   Attempt #$attempt\n";
            
            if ($attempt < 3) {
                throw new \RuntimeException("Temporary failure");
            }
            
            return "Success after retries";
        },
        maxRetries: 3,
        initialDelay: 0.1
    );
    
    echo "   Final result: $result\n";
} catch (\Exception $e) {
    echo "   Failed after all retries: " . $e->getMessage() . "\n";
}

echo "\n=== Example Complete ===\n";
