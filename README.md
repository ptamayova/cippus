<div align="center">
    <h1>Cippus</h1>
    <p><em>Mark the line. Build with confidence.</em></p>
</div>

<p align="center">
    <a href="https://github.com/ptamayova/cippus/releases"><img src="https://img.shields.io/github/v/release/ptamayova/cippus?style=flat-square" alt="Latest release"></a>
    <a href="https://github.com/ptamayova/cippus/blob/main/composer.json"><img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="Requires PHP 8.3 or later"></a>
    <a href="https://github.com/ptamayova/cippus/blob/main/composer.json"><img src="https://img.shields.io/badge/Laravel-12%20%7C%2013-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Supports Laravel 12 and 13"></a>
    <a href="https://github.com/ptamayova/cippus/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/ptamayova/cippus/tests.yml?branch=main&label=Tests&style=flat-square" alt="Tests"></a>
    <a href="https://github.com/ptamayova/cippus/blob/main/LICENSE.md"><img src="https://img.shields.io/github/license/ptamayova/cippus?style=flat-square" alt="MIT License"></a>
</p>

Cippus gives a Laravel application an editable baseline for AI-assisted development. It lays down clear instructions, practical skills, local services, and a quality toolbelt so agents can write application code with precision—without locking the application into opaque package conventions. Everything it installs belongs to your application and is meant to be adapted.

## What Cippus marks

Cippus starts with a pragmatic architecture: Form Requests validate HTTP input, thin controllers translate requests into responses, and focused Actions hold each business use case. Actions use Eloquent directly, keep reusable constraints in model scopes, and wrap related writes in transactions. This keeps behavior reusable from controllers, commands, and jobs without adding repository or service layers by default.

The installer adds:

- `AGENTS.md`, `CLAUDE.md`, and starter skills for implementation, testing, requirements, and pull-request descriptions;
- a FrankenPHP and PostgreSQL Docker setup;
- Pint, PHPStan/Larastan, and Rector configuration; and
- Composer scripts and compatible development dependencies for the quality suite.

Read the generated [`AGENTS.md`](resources/stubs/AGENTS.md) before handing work to an AI agent. It is a strong starting line, not a rigid rulebook.

## Installation

Install Cippus as a development dependency, then run the installer from the application root:

```bash
composer require --dev mdecode/cippus
php artisan cippus:install
```

When `AGENTS.md` or `CLAUDE.md` already exists, Cippus asks whether to attach its baseline or replace the file; attaching is the default. Other existing files, Composer constraints, and scripts are preserved.

Use `--force` only when you intentionally want to replace generated resources:

```bash
php artisan cippus:install --force
```

For an offline or staged setup, `--no-composer` writes resources and Composer scripts without installing dependencies:

```bash
php artisan cippus:install --no-composer
```

Run the installer again without this option when Composer is available.

## Make it yours

The installed files are application-owned. Customize the instructions, skills, Docker files, and quality configuration for the application you are building. Re-running Cippus preserves them unless `--force` is supplied.

The shipped templates are available here for review:

- [`AGENTS.md`](resources/stubs/AGENTS.md) — architecture, clean-code rules, and testing strategy.
- [`CLAUDE.md`](resources/stubs/CLAUDE.md) — equivalent project instructions for Claude.
- [`laravel-actions`](resources/stubs/skills/laravel-actions/SKILL.md) — focused use-case implementation.
- [`laravel-testing`](resources/stubs/skills/laravel-testing/SKILL.md) — efficient Pest coverage.
- [`requirements-to-gh-issue`](resources/stubs/skills/requirements-to-gh-issue/SKILL.md) — repository-grounded issue planning.
- [`pr-description`](resources/stubs/skills/pr-description/SKILL.md) — evidence-based pull-request descriptions.

## Running the quality gate

```bash
# Apply Rector and Pint fixes
composer lint

# Check Rector and Pint without changing files
composer lint:check

# Check Pest type coverage
composer test:type-coverage

# Run Pest with exact coverage
composer test:unit

# Run PHPStan/Larastan
composer test:types

# Run the complete quality suite
composer test
```

The included Docker setup runs FrankenPHP and PostgreSQL:

```bash
docker compose up --build
```

The exact-coverage command requires Xdebug, which is included in the generated FrankenPHP image.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## Contributing

Thank you for considering contributing to Cippus. Please review the [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security vulnerabilities

Please review the [security policy](.github/SECURITY.md) to report security vulnerabilities.

## Credits

- [ptamayova](https://github.com/ptamayova)
- [All Contributors](../../contributors)

## License

Cippus is open-sourced software licensed under the [MIT license](LICENSE.md).
