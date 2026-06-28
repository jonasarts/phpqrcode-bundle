# Contributing

This bundle follows the shared jonasarts Symfony-bundle conventions.
**`registry2-bundle` is the canonical template**; this bundle is kept aligned
with it and checked by `registry2-bundle/tools/drift-lint.sh`.

This file and `CLAUDE.md` are `export-ignore`d — they live in the repository, not
in the Composer dist.

## Requirements

- PHP 8.4+ with the `gd` extension (QR rendering and the unit tests need it)
- Composer 2

No external services are needed for the test suites.

## Local workflow

```bash
composer install
composer cs-check               # PHP-CS-Fixer (report)
composer rector-check           # Rector (report)
composer phpstan                # PHPStan
composer test                   # unit suite
composer test-integration       # integration suite (boots a test kernel, HTTP routes)
```

Apply autofixes with `composer cs` and `composer rector`. Always run
`composer normalize` after editing `composer.json`.

## Gold standard

- **composer.json**: no `version` field; `type: "symfony-bundle"`,
  `minimum-stability: "stable"`, `prefer-stable: true`,
  `config.sort-packages: true`. `require` lists `php`, `ext-gd`,
  `chillerlan/php-qrcode` and the `symfony/*` runtime components at
  `^7.0 || ^8.0`. `symfony/yaml` is a **runtime** dependency (the bundle imports
  `Resources/config/services.yaml`). Dev tooling pinned: PHPUnit
  `^12.0 || ^13.0`, php-cs-fixer `^3.95`, phpstan `^2.0`, rector `^2.0`.
  Field order/format is owned by `composer normalize`; the bundle must pass
  `composer validate --strict` and `composer normalize --dry-run`.
- **Layout**: tests in `tests/` (lowercase); PHPUnit config `phpunit.dist.xml`;
  test suites `unit` (`tests/PHPQRCode`, `tests/DependencyInjection`) and
  `integration` (`tests/PHPQRCodeControllerTest.php`).
- **Scripts**: `cs`, `cs-check`, `rector`, `rector-check`, `phpstan`,
  `test` (= `phpunit --testsuite unit`), `test-integration`.
- **Docs**: `docs/index.md`, `01-install.md`, `02-basic-usage.md`, `docs/test.md`;
  changelog is the root `CHANGELOG.md` (Keep a Changelog + SemVer).
- **Dist hygiene**: dev/test/doc/CI/tooling files are kept out of the dist via
  `.gitattributes export-ignore`. `composer.lock` is git-ignored.

## CI gates

`.github/workflows/ci.yml` enforces on every push/PR: `composer validate
--strict`, `composer normalize --dry-run`, `cs-check`, `rector-check`,
`phpstan`, `composer-require-checker`, `composer-unused`, `composer audit`, the
unit suite on a PHP 8.4/8.5 × highest/lowest matrix, and the integration suite.
The `gd` extension is enabled in CI.

`composer-unused.php` ignores `symfony/yaml` (loader-imported `services.yaml`)
and `ext-gd` (used transitively by `chillerlan/php-qrcode`) — both are real
runtime dependencies the tool cannot detect.

## Releasing

1. Move the `CHANGELOG.md` `Unreleased` section to the new version.
2. Ensure CI is green.
3. For a new major, check backward compatibility and bump SemVer accordingly.
4. Tag `vX.Y.Z`, push the tag, create the GitHub release (Packagist syncs).
5. Spot-check `composer archive` — no `tests/`, `docs/`, CI/tooling configs,
   `CONTRIBUTING.md` or `CLAUDE.md` in the dist.
