---
name: cippus-development
description: >
  Configure and apply the Cippus package in Laravel applications.
license: MIT
metadata:
  author: mdecode
---

# Cippus

Use this skill when a Laravel application needs an editable Cippus baseline for precise AI-assisted development.

## Primary Goal

- install a customizable, application-owned baseline for AI instructions, Laravel development and collaboration skills, quality tooling, and Docker services

## Workflow

### 1. Inspect the Laravel application

- confirm the working directory contains `artisan` and `composer.json`
- inspect existing `AGENTS.md`, `CLAUDE.md`, `.agents/skills`, Docker, Rector, Pint, and PHPStan files before installing
- preserve app-specific Composer constraints and scripts

### 2. Install the baseline

```bash
composer require --dev mdecode/cippus
php artisan cippus:install
```

The command uses an interactive selector when existing `AGENTS.md` and `CLAUDE.md` files are found: append the package baseline (the default) or replace the file. It preserves other existing resource files and Composer values, adds missing scripts, then uses `composer require --dev` without versions so Composer can select and record the newest compatible development dependencies.

Use `--no-composer` only when Composer cannot run yet; re-run the installer without it to install dependencies later. Use `--force` only when replacing the generated resource files is intentional.

### 3. Customize the installed files

- adapt `AGENTS.md` and `CLAUDE.md` to the application's actual architecture
- read `AGENTS.md` before making changes: it establishes clean-code and SOLID conventions through Form Requests, thin controllers, focused Actions, direct Eloquent access, model scopes, and transactions for related writes
- use `laravel-actions` and `laravel-testing` for application behavior and coverage decisions
- use `requirements-to-gh-issue` and `pr-description` for repository-grounded planning and pull-request work; adapt every installed skill if the application architecture changes
- add or refine skills under `.agents/skills/`
- adjust `docker-compose.yml` and `docker/laravel/Dockerfile` for local infrastructure
- tune `phpstan.neon`, `pint.json`, and `rector.php` as the application evolves

### 4. Validate the application

Run the narrow command needed during development, then run `composer test` for the full quality gate (PHPStan, formatting, type coverage, and Pest). Use `docker compose up --build` to validate the FrankenPHP and PostgreSQL setup.

## Rules, References, and Templates

Read before executing:

- `README.md`
- the consuming application's `AGENTS.md`
- the consuming application's `CLAUDE.md`
- the consuming application's `.agents/skills/`
- the consuming application's `composer.json`

## Examples

- scaffold without network access: `php artisan cippus:install --no-composer`, review the diff, then run `composer update`
- intentionally reset only package-generated resources: commit current customizations, then run `php artisan cippus:install --force`

## Anti-patterns

- do not document package internals here; keep the skill focused on adoption in Laravel apps
- attach the package baseline to customized instruction files unless replacement is intentional; `--force` replaces package-generated resource destinations
- do not delete existing application-specific Composer scripts or constraints
- do not treat the generated rules and tool configuration as immutable vendor files
