CHANGE LOG
==========

V 6.4.5
-------

- Moved from annotations to attributes
- Merged PR7
- Minor code cleanup

V 6.3.1
-------

- Applied missing PHP 8.1 changes

V 6.3.0
-------

- Requires PHP 8.1
- Updated for Symfony 6.3 Branch

V 6.0.5
-------

- Changed QRCode Version from 7 to auto to avoid code length issues
- Renamed files from .yml to .yaml

V 6.0.0
-------

- Removed old QR Code lib
- Moved to chillerlan/php-qrcode for QR Code generation
- Update for PHP 8.* compatibility
- Update for Symfony 5.* compatibility
- Test-Release for Symfony 6.x
- Not ready for production
- Breaking changes in the PHPQRCodeInterface methods!!!
  (bool $saveandprint = false was removed)

V 5.0.0
-------

- Update for Symfony 5.* compatibility
- Updated docs & examples for Symfony 4|5

V 4.0.2
-------

- Update for Symfony 4.3 compatibility

V 4.0.1
-------

- Updated TreeBuilder to support Symfony 5.0

V 2.0.1
-------

- Fixed psr-4 config

V 2.0.0
-------

- Release for Symfony 3.x
- Updated phpqrcode lib to t0k4rt/phpqrcode commit d213c48 on 2 Nov 2016
- Added SVG routes
- Updated controller responses to proper Symfony\Component\HttpFoundation\Response objects  


V 1.1.3
-------

- Release vor Symfony 2.x
