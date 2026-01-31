# Async Patterns Lab ⚡

[![Tests](https://github.com/syeedalireza/async-patterns-lab/workflows/CI/badge.svg)](https://github.com/syeedalireza/async-patterns-lab/actions)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg)](https://phpstan.org/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Academic research package comparing async/concurrent patterns in PHP 8.2+: **Fibers**, **Amphp**, **ReactPHP**, with comprehensive benchmarks and analysis.

## 🎯 Purpose

Research and practical comparison of modern asynchronous programming patterns in PHP:
- **PHP 8.2+ Fibers** - Native cooperative multitasking
- **Amphp v3** - Event loop and coroutines
- **ReactPHP** - Event-driven async I/O

## 🚀 Features

✅ **PHP 8.2+ Fibers**: FiberScheduler, FiberPool for concurrent execution  
✅ **Amphp v3**: HTTP client, async coroutines  
✅ **ReactPHP**: Event loop, promise-based async  
✅ **Performance Benchmarks**: Empirical throughput and latency comparisons  
✅ **Research Documentation**: Academic analysis with data  
✅ **Comprehensive Tests**: PHPUnit with 90%+ coverage  
✅ **Quality Assurance**: PHPStan Level 9, Psalm  

## 📦 Installation

```bash
composer require syeedalireza/async-patterns-lab --dev
```

## 🔧 Quick Start

### Using PHP Fibers

```php
use AsyncPatternsLab\Fibers\FiberScheduler;

$scheduler = new FiberScheduler();

$scheduler->schedule(fn() => fetchData('url1'), 'task1');
$scheduler->schedule(fn() => fetchData('url2'), 'task2');

$results = $scheduler->run();
// ['task1' => 'data1', 'task2' => 'data2']
```

### Using Fiber Pool

```php
use AsyncPatternsLab\Fibers\FiberPool;

$pool = new FiberPool(maxConcurrency: 10);

$results = $pool->map([
    fn() => processTask1(),
    fn() => processTask2(),
    fn() => processTask3(),
]);
```

## 📊 Research Findings

### Performance Comparison

| Pattern | Throughput (ops/s) | Memory (MB) | Best For |
|---------|-------------------|-------------|----------|
| **Fibers** | 25,000 | 10 | Simple concurrent tasks |
| **Amphp** | 22,000 | 15 | Complex async workflows |
| **ReactPHP** | 20,000 | 12 | Event-driven apps |

See [full research paper](docs/research/async-comparison-study.md) for detailed analysis.

## 🧪 Running Tests

```bash
# Run all tests
composer test

# Run with coverage
./vendor/bin/phpunit --coverage-html build/coverage

# Static analysis
composer phpstan
```

## 📖 Documentation

- [Research Paper](docs/research/async-comparison-study.md) - Empirical comparison study
- [Examples](examples/) - Working code examples
- [Contributing](CONTRIBUTING.md) - Contribution guidelines

## 🤝 Contributing

Contributions welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## 📄 License

MIT License - see [LICENSE.md](LICENSE.md)

## 👤 Author

**Alireza Aminzadeh**
- GitHub: [@syeedalireza](https://github.com/syeedalireza)
- Packagist: [syeedalireza](https://packagist.org/users/syeedalireza/)

---

**Made for the PHP Async Community** 🚀
