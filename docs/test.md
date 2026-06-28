Testing the bundle
==================

This bundle ships a real test suite (not just HTTP-200 smoke tests) plus the
usual static-analysis and code-style tooling. All commands are exposed as
Composer scripts, mirroring the other jonasarts bundles.

## Requirements

- PHP 8.4+ with the `gd` extension (the QR rendering and the unit tests need it)
- Composer

## Install the dev dependencies

From the bundle root:

```bash
composer install
```

This pulls in PHPUnit, PHPStan, Rector and PHP-CS-Fixer (see `require-dev`).

## Composer scripts

| Command | What it runs |
|---------|--------------|
| `composer test` | PHPUnit – **unit** suite (default suite) |
| `composer test-integration` | PHPUnit – **integration** suite |
| `composer phpstan` | Static analysis (`phpstan.dist.neon`) |
| `composer cs-check` | PHP-CS-Fixer dry-run (report only) |
| `composer cs` | PHP-CS-Fixer – apply fixes |
| `composer rector-check` | Rector dry-run (report only) |
| `composer rector` | Rector – apply changes |

A full local check before tagging:

```bash
composer cs-check
composer rector-check
composer phpstan
composer test
composer test-integration
```

## Test suites

The suites are defined in `phpunit.dist.xml`.

### unit — `tests/PHPQRCode/` and `tests/DependencyInjection/`

Pure unit tests. No Symfony kernel is booted.

`PHPQRCodeTest` covers the `PHPQRCode` service — the real proof that the v6
rewrite and the colour fix work:

- PNG is a valid image (magic bytes) and carries the public cache headers + ETag.
- SVG is well-formed markup (parses as XML).
- **Content check:** the rendered PNG is decoded again with the chillerlan
  reader and the embedded payload must equal the input string.
- **Colour regression (the historical bug):** custom foreground/background
  colours are honoured. A red-on-white code contains red + white and no
  default-black; a stronger blue-on-yellow case proves *both* colours are
  wired independently (no coincidental default), and the defaults still render
  black-on-white.
- **ECC level:** `L`/`M`/`Q`/`H` actually change the output, the setter is
  case-insensitive, and an invalid level falls back to `L`.
- **Edge cases:** `margin = 0` still produces a decodable code; the ETag is
  deterministic for equal input and differs for different input.

`BundleExtensionTest` covers the DI layer (`configure()` + `loadExtension()`):
default and overridden config parameters, the `PHPQRCodeInterface` alias, the
controller service registration and the pinned `phpqrcode` extension alias.

Run only the unit suite:

```bash
composer test
# or
vendor/bin/phpunit --testsuite unit
```

### integration — `tests/PHPQRCodeControllerTest.php`

Functional tests that boot the minimal `tests/TestKernel.php` (FrameworkBundle
+ this bundle, routes imported from the controller) and drive the HTTP routes
with a `WebTestCase` client:

- `/qr/png`, `/qr/png/{level}/{size}/{margin}`, `/qr/svg`, `/qr/svg/...` return
  `200` with the correct `Content-Type`, non-empty body, PNG magic bytes /
  `<svg>` markup and the `public` cache header.
- Input hardening: an invalid `level` returns `404` (route requirement), an
  out-of-range `size` returns `400`, and an over-long `?text=` returns `400`.

Run only the integration suite:

```bash
composer test-integration
# or
vendor/bin/phpunit --testsuite integration
```

The `TestKernel` writes its cache/logs to the system temp directory, so no
project-level `var/` is required.

## Running a single test

```bash
vendor/bin/phpunit --testsuite unit --filter testPngHonorsCustomColors
```

## Coverage

Coverage needs Xdebug or PCOV. With one of them enabled:

```bash
XDEBUG_MODE=coverage vendor/bin/phpunit --testsuite unit --coverage-text
```

The covered sources are restricted to `src/` (see the `<source>` block in
`phpunit.dist.xml`).

## Continuous integration

`.github/workflows/ci.yml` runs the whole chain on a PHP 8.4 / Symfony 8.1
matrix: `composer test`, `composer test-integration`, PHPStan,
`composer rector-check` and `composer cs-check`. A green pipeline is the
release gate.

[Return to the index.](index.md)
