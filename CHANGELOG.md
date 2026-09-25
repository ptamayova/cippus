# Release Notes

## [Unreleased](https://github.com/ptamayova/cippus/compare/2.0.0...HEAD)

## [2.0.0](https://github.com/ptamayova/cippus/compare/1.5.0...2.0.0) - 2026-09-25

### Changed

- Renamed Ai Dev Quickstart to Cippus.
- Renamed the Composer package to `mdecode/cippus`, the PHP namespace to `Mdecode\\Cippus`, and the installer command to `cippus:install`.
- Rewrote the documentation and bundled Cippus development skill around the new identity.

### Upgrade guide

- Remove `mdecode/ai-dev-quickstart`, then require `mdecode/cippus` as a development dependency.
- Replace any `AiDevQuickstart\\AiDevQuickstart` imports with `Mdecode\\Cippus` and run `php artisan cippus:install`.
- Existing application-owned files remain yours; review them before deliberately re-running the installer with `--force`.

## [1.5.0](https://github.com/ptamayova/cippus/compare/1.4.1...1.5.0) - 2026-09-25

### Removed

- The installer no longer adds the `pr-review` skill to target Laravel applications.

Existing applications retain an already-installed `.agents/skills/pr-review` directory until it is removed manually.

## [1.4.1](https://github.com/ptamayova/cippus/compare/1.4.0...1.4.1) - 2026-09-06

<!-- Release notes generated using configuration in .github/release.yml at 1.4.1 -->
**Full Changelog**: https://github.com/ptamayova/cippus/compare/1.4.0...1.4.1

## [1.4.0](https://github.com/ptamayova/cippus/compare/1.3.1...1.4.0) - 2026-09-06

<!-- Release notes generated using configuration in .github/release.yml at 1.4.0 -->
**Full Changelog**: https://github.com/ptamayova/cippus/compare/1.3.1...1.4.0

## [1.3.1](https://github.com/ptamayova/cippus/compare/1.3.0...1.3.1) - 2026-09-05

<!-- Release notes generated using configuration in .github/release.yml at 1.3.1 -->
**Full Changelog**: https://github.com/ptamayova/cippus/compare/1.3.0...1.3.1

## [1.3.0](https://github.com/ptamayova/cippus/compare/1.2.0...1.3.0) - 2026-08-23

<!-- Release notes generated using configuration in .github/release.yml at 1.3.0 -->
**Full Changelog**: https://github.com/ptamayova/cippus/compare/1.2.0...1.3.0

## [1.2.0](https://github.com/ptamayova/cippus/compare/1.1.0...1.2.0) - 2026-08-23

<!-- Release notes generated using configuration in .github/release.yml at 1.2.0 -->
**Full Changelog**: https://github.com/ptamayova/cippus/compare/1.1.0...1.2.0

## [1.1.0](https://github.com/ptamayova/cippus/compare/1.0.0...1.1.0) - 2026-08-23

<!-- Release notes generated using configuration in .github/release.yml at 1.1.0 -->
**Full Changelog**: https://github.com/ptamayova/cippus/compare/1.0.0...1.1.0

## [1.0.0](https://github.com/ptamayova/cippus/compare/v0.1.0...1.0.0) - 2026-08-23

<!-- Release notes generated using configuration in .github/release.yml at 1.0.0 -->
**Full Changelog**: https://github.com/ptamayova/cippus/commits/1.0.0

## [v0.1.0](https://github.com/ptamayova/cippus/compare/...v0.1.0) - 202x-xx-xx

Initial pre-release.
