<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Benchmarks;

/**
 * Memory profiling utilities for async patterns
 */
final class MemoryProfiler
{
    private int $startMemory = 0;
    private int $peakMemory = 0;
    private array $snapshots = [];

    /**
     * Start memory profiling
     */
    public function start(): void
    {
        $this->startMemory = memory_get_usage(true);
        $this->peakMemory = memory_get_peak_usage(true);
        $this->snapshots = [];
    }

    /**
     * Take a memory snapshot
     *
     * @param string $label
     */
    public function snapshot(string $label): void
    {
        $this->snapshots[] = [
            'label' => $label,
            'memory' => memory_get_usage(true),
            'peak' => memory_get_peak_usage(true),
            'time' => microtime(true),
        ];
    }

    /**
     * Get profiling results
     *
     * @return array<string, mixed>
     */
    public function getResults(): array
    {
        $currentMemory = memory_get_usage(true);
        $currentPeak = memory_get_peak_usage(true);

        return [
            'start_memory_bytes' => $this->startMemory,
            'current_memory_bytes' => $currentMemory,
            'peak_memory_bytes' => $currentPeak,
            'memory_used_bytes' => $currentMemory - $this->startMemory,
            'snapshots' => $this->snapshots,
        ];
    }

    /**
     * Format bytes to human-readable string
     *
     * @param int $bytes
     * @return string
     */
    public function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Generate memory report
     *
     * @return string
     */
    public function generateReport(): string
    {
        $results = $this->getResults();
        
        $report = "# Memory Profiling Report\n\n";
        $report .= "Start: " . $this->formatBytes($results['start_memory_bytes']) . "\n";
        $report .= "Current: " . $this->formatBytes($results['current_memory_bytes']) . "\n";
        $report .= "Peak: " . $this->formatBytes($results['peak_memory_bytes']) . "\n";
        $report .= "Used: " . $this->formatBytes($results['memory_used_bytes']) . "\n\n";

        if (!empty($results['snapshots'])) {
            $report .= "## Snapshots\n\n";
            foreach ($results['snapshots'] as $snapshot) {
                $report .= sprintf(
                    "- %s: %s (Peak: %s)\n",
                    $snapshot['label'],
                    $this->formatBytes($snapshot['memory']),
                    $this->formatBytes($snapshot['peak'])
                );
            }
        }

        return $report;
    }
}
