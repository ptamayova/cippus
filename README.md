<div align="center">
    <h1>Ai Dev Quickstart</h1>
</div>

<p align="center">
    <a href="https://github.com/ptamayova/ai-dev-quickstart/releases"><img src="https://img.shields.io/github/v/release/ptamayova/ai-dev-quickstart?style=flat-square" alt="Latest release"></a>
    <a href="https://github.com/ptamayova/ai-dev-quickstart/blob/main/composer.json"><img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="Requires PHP 8.3 or later"></a>
    <a href="https://github.com/ptamayova/ai-dev-quickstart/blob/main/composer.json"><img src="https://img.shields.io/badge/Laravel-12%20%7C%2013-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Supports Laravel 12 and 13"></a>
    <a href="https://github.com/ptamayova/ai-dev-quickstart/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/ptamayova/ai-dev-quickstart/tests.yml?branch=main&label=Tests&style=flat-square" alt="Tests"></a>
    <a href="https://github.com/ptamayova/ai-dev-quickstart/blob/main/LICENSE.md"><img src="https://img.shields.io/github/license/ptamayova/ai-dev-quickstart?style=flat-square" alt="MIT License"></a>
</p>

Ai Dev Quickstart gives a Laravel application an editable development baseline. Its installer adds AI instructions and Laravel skills for implementation, testing, requirements, pull-request descriptions, and architecture-aware reviews; a FrankenPHP and PostgreSQL Docker setup; Pint, PHPStan/Larastan, and Rector configuration; plus Composer scripts for the quality suite. Everything it installs belongs to your application and can be customized.

## Proposed architecture

The quickstart provides a pragmatic Laravel architecture rather than a rigid framework. It applies SOLID and clean-code principles through focused boundaries: Form Requests validate HTTP input, thin controllers translate requests into responses, and single-purpose Actions hold each business use case. Actions use Eloquent directly, keep reusable query constraints in model scopes, and wrap related writes in transactions. This keeps application behavior reusable from HTTP controllers, commands, and jobs without adding repository or service layers by default.

The generated [AGENTS.md](resources/stubs/AGENTS.md) explains these conventions and the accompanying testing strategy in detail. Treat it as an editable starting point: adapt it to your application as its domains and constraints become clear.

## Read after installation

The installer writes the following files into your application's root or `.agents/skills/` directory. Read them before asking an AI agent to make changes, then customize them to fit the application.

- [`AGENTS.md`](resources/stubs/AGENTS.md) — architecture, clean-code rules, and testing strategy.
- [`CLAUDE.md`](resources/stubs/CLAUDE.md) — equivalent project instructions for Claude.
- [`laravel-actions`](resources/stubs/skills/laravel-actions/SKILL.md) — implements use cases with thin HTTP boundaries and focused Actions.
- [`laravel-testing`](resources/stubs/skills/laravel-testing/SKILL.md) — selects efficient Pest coverage for Laravel behavior.
- [`requirements-to-gh-issue`](resources/stubs/skills/requirements-to-gh-issue/SKILL.md) — turns a requirement into a repository-grounded GitHub issue.
- [`pr-description`](resources/stubs/skills/pr-description/SKILL.md) — drafts evidence-based pull-request descriptions.

These links show the shipped templates; after installation, use the matching files in your application as the source of truth.

## Installation

Install the package as a development dependency, then run the installer from your Laravel application's root:

```bash
composer require --dev mdecode/ai-dev-quickstart
php artisan ai-dev-quickstart:install
```

The installer adds:

- `AGENTS.md`, `CLAUDE.md`, and starter Laravel skills in `.agents/skills/`, including requirement-to-issue and PR-description workflows;
- `docker-compose.yml` and a FrankenPHP Dockerfile with PostgreSQL;
- `pint.json`, `phpstan.neon`, and `rector.php`;
- quality scripts in `composer.json`; and
- compatible development dependencies for Pest, PHPStan/Larastan, and Rector.

When `AGENTS.md` or `CLAUDE.md` already exists, the installer asks whether to append the baseline or replace it; appending is the default. Other existing files, Composer constraints, and scripts are preserved. Use `--force` only when you intentionally want to replace generated files:

```bash
php artisan ai-dev-quickstart:install --force
```

For an offline or staged setup, `--no-composer` writes resources and Composer scripts without installing dependencies. Run the installer again without this option when Composer is available:

```bash
php artisan ai-dev-quickstart:install --no-composer
```

## Usage

Customize the installed instructions, skills, Docker files, and quality configuration for your application. Re-running the installer preserves them unless `--force` is supplied.

### Docker

Start the included FrankenPHP application and PostgreSQL services:

```bash
docker compose up --build
```

The application is available at `http://localhost:8000` by default. Set `APP_PORT` or `DB_*` values to customize the Compose services.

### Quality commands

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

The exact-coverage test command requires Xdebug, which is included in the generated FrankenPHP image.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Ai Dev Quickstart! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [ptamayova](https://github.com/ptamayova)
- [All Contributors](../../contributors)

## License

Ai Dev Quickstart is open-sourced software licensed under the [MIT license](LICENSE.md).
