<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;

// Both are real runtime dependencies composer-unused cannot detect:
// - symfony/yaml: the bundle imports Resources/config/services.yaml via
//   $container->import(), which needs the YAML loader.
// - ext-gd: required by chillerlan/php-qrcode to render PNG QR codes; used
//   transitively, not through a direct symbol reference in this bundle.
return static fn (Configuration $config): Configuration => $config
    ->addNamedFilter(NamedFilter::fromString('symfony/yaml'))
    ->addNamedFilter(NamedFilter::fromString('ext-gd'));
