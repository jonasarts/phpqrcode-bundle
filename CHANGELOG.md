# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [8.0.0] - 2026-06-28

### Added

- Explicit `symfony/* ^7.0 || ^8.0` constraints and `ext-gd`.
- Real test suite: service unit tests decode the rendered QR and verify the
  embedded content and colors; functional tests assert content-type, magic bytes,
  cache headers and the 400/404 paths.
- `phpunit.dist.xml`, PHPStan, PHP-CS-Fixer and a GitHub Actions CI matrix.

### Changed

- Requires PHP 8.4 and Symfony `^7.0 || ^8.0`.
- Bumped `chillerlan/php-qrcode` to `^6.0` (new output API: `outputInterface`,
  Enum-style ECC, module values).
- Migrated the bundle to `AbstractBundle`; removed the deprecated `Extension`
  class.
- Controller now uses constructor dependency injection (no more `setContainer` /
  `getParameter`).
- Hardened the controller: optional access role (`phpqrcode.access.role`), `?text=`
  length limit (`phpqrcode.limits.max_text_length`, default 1500 → HTTP 400),
  `size` clamped to 1–10 and `margin` validated (→ HTTP 400), HTTP caching
  (public, max-age, ETag).
- PHPStan level 8 clean (no baseline, no ignores) — array-shape typed config,
  `Request::getString()` for `?text=`.
- **Breaking:** minimum PHP/Symfony raised; manual service registration is no
  longer required (see the install docs).

### Removed

- The duplicated option/response building in the service (shared `buildOptions` /
  `toResponse`).
- The `/qr/test` debug route.

### Fixed

- `$back_color` / `$fore_color` are now actually applied to the rendered QR code
  (PNG via `bgColor` + `moduleValues`, SVG via fill colors). Previously these
  parameters were ignored.

## [6.4.5]

- Moved from annotations to attributes.
- Merged PR7.
- Minor code cleanup.

## [6.3.1]

- Applied missing PHP 8.1 changes.

## [6.3.0]

- Requires PHP 8.1.
- Updated for the Symfony 6.3 branch.

## [6.0.5]

- Changed QR code version from 7 to auto to avoid code-length issues.
- Renamed files from `.yml` to `.yaml`.

## [6.0.0]

- Removed the old QR code lib; moved to `chillerlan/php-qrcode` for QR code
  generation.
- Update for PHP 8.* compatibility.
- Update for Symfony 5.* compatibility.
- Test release for Symfony 6.x (not ready for production).
- Breaking changes in the `PHPQRCodeInterface` methods (the
  `bool $saveandprint = false` argument was removed).

## [5.0.0]

- Update for Symfony 5.* compatibility.
- Updated docs & examples for Symfony 4|5.

## [4.0.2]

- Update for Symfony 4.3 compatibility.

## [4.0.1]

- Updated `TreeBuilder` to support Symfony 5.0.

## [2.0.1]

- Fixed PSR-4 config.

## [2.0.0]

- Release for Symfony 3.x.
- Updated phpqrcode lib to `t0k4rt/phpqrcode` commit d213c48 (2 Nov 2016).
- Added SVG routes.
- Updated controller responses to proper
  `Symfony\Component\HttpFoundation\Response` objects.

## [1.1.3]

- Release for Symfony 2.x.
