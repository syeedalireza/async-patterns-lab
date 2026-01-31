# Contributing to Async Patterns Lab

Thank you for considering contributing to this project!

## Development Setup

```bash
git clone https://github.com/syeedalireza/async-patterns-lab.git
cd async-patterns-lab
composer install
```

## Running Tests

```bash
# Run all tests
composer test

# Run with coverage
./vendor/bin/phpunit --coverage-html build/coverage

# Run static analysis
composer phpstan
```

## Code Style

This project follows PSR-12 coding standards. Please run:

```bash
composer cs-fix
```

## Pull Request Process

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests and static analysis
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

## Reporting Issues

Please use GitHub Issues to report bugs or suggest features.

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
