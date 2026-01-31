<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Swoole;

/**
 * Swoole HTTP Server implementation (requires ext-swoole)
 * This is a demonstration class - requires Swoole extension in production
 */
final class SwooleServer
{
    private array $config;

    public function __construct(
        private readonly string $host = '127.0.0.1',
        private readonly int $port = 9501
    ) {
        $this->config = [
            'worker_num' => 4,
            'enable_coroutine' => true,
            'max_coroutine' => 3000,
        ];
    }

    /**
     * Check if Swoole extension is available
     */
    public function isAvailable(): bool
    {
        return extension_loaded('swoole');
    }

    /**
     * Get server configuration
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Set configuration option
     *
     * @param string $key
     * @param mixed $value
     */
    public function setConfig(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }

    /**
     * Get server host
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get server port
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Simulate concurrent request handling
     *
     * @param array<string> $requests
     * @return array<string, mixed>
     */
    public function handleConcurrentRequests(array $requests): array
    {
        $results = [];
        
        foreach ($requests as $index => $request) {
            $results["request_$index"] = [
                'status' => 200,
                'body' => "Processed: $request",
                'time' => microtime(true),
            ];
        }

        return $results;
    }

    /**
     * Get performance metrics
     *
     * @return array<string, mixed>
     */
    public function getMetrics(): array
    {
        return [
            'workers' => $this->config['worker_num'],
            'max_coroutines' => $this->config['max_coroutine'],
            'coroutine_enabled' => $this->config['enable_coroutine'],
            'host' => $this->host,
            'port' => $this->port,
        ];
    }
}
