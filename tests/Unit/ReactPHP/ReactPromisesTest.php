<?php

declare(strict_types=1);

namespace AsyncPatternsLab\Tests\Unit\ReactPHP;

use AsyncPatternsLab\ReactPHP\ReactPromises;
use PHPUnit\Framework\TestCase;

final class ReactPromisesTest extends TestCase
{
    private ReactPromises $promises;

    protected function setUp(): void
    {
        $this->promises = new ReactPromises();
    }

    public function testCreateResolvedPromise(): void
    {
        $promise = $this->promises->createResolved(42);
        
        $result = null;
        $promise->then(function ($value) use (&$result) {
            $result = $value;
        });

        $this->assertEquals(42, $result);
    }

    public function testCreateRejectedPromise(): void
    {
        $exception = new \Exception('Test error');
        $promise = $this->promises->createRejected($exception);
        
        $error = null;
        $promise->then(null, function ($e) use (&$error) {
            $error = $e;
        });

        $this->assertSame($exception, $error);
    }

    public function testChainPromises(): void
    {
        $promise = $this->promises->createResolved(10);
        
        $result = null;
        $chained = $this->promises->chain(
            $promise,
            fn($x) => $x * 2
        );

        $chained->then(function ($value) use (&$result) {
            $result = $value;
        });

        $this->assertEquals(20, $result);
    }
}
