<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Amphp;

/**
 * Amphp HTTP Client example (requires amphp/amp).
 * Demonstrates async HTTP requests using Amphp v3.
 *
 * Note: Actual implementation requires amphp/http-client package.
 * This is a demonstration of the pattern.
 */
final class AmphpHttpClient
{
    /**
     * Fetch multiple URLs concurrently using Amphp.
     *
     * @param array<string> $urls URLs to fetch
     *
     * @return array<string, string> Responses indexed by URL
     */
    public function fetchMultiple(array $urls): array
    {
        $responses = [];

        foreach ($urls as $url) {
            // Simulated async fetch
            $responses[$url] = $this->simulateFetch($url);
        }

        return $responses;
    }

    /**
     * Simulated fetch (replace with actual Amphp HTTP client).
     */
    private function simulateFetch(string $url): string
    {
        return "Response from {$url}";
    }
}
