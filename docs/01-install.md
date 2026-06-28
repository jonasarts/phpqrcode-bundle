Setting up the bundle
=====================

## Requirements

- PHP 8.4+ with the `gd` extension
- Symfony 8.x

## Install the bundle

Execute this console command in your project:

``` bash
composer require jonasarts/phpqrcode-bundle
```

## Enable the bundle

Composer enables the bundle for you in config/bundles.php

The bundle registers its own services (the `PHPQRCode` service, the
`PHPQRCodeInterface` alias and the controller). **No manual service
registration is required** — just type-hint
`jonasarts\Bundle\PHPQRCodeBundle\PHPQRCode\PHPQRCodeInterface` where you
need it.

Register the routes:

Add a routing configuration in `config/routes` directory:

```yaml
#config/routes/phpqrcode.yaml
phpqrcode:
    resource: '@PHPQRCodeBundle/src/Controller/'
    type: attribute
```

or

```yaml
#config/routes/phpqrcode.yaml
phpqrcode:
    resource: "@PHPQRCodeBundle/src/Resources/config/routing.yaml"
    prefix:   /
```

Optional, add a configuration:

```yaml
# config/packages/phpqrcode.yaml
phpqrcode:
    default:
        level: Q          # L | M | Q | H
        size: 4           # 1 - 10
        margin: 3         # quiet-zone modules
    limits:
        max_text_length: 1500   # ?text= is rejected with HTTP 400 above this
    access:
        role: null        # e.g. 'ROLE_USER' to protect the routes; null = public
```

> Security note: the QR routes are public by default. If the codes are not
> meant to be world-readable, set `phpqrcode.access.role` to an appropriate
> role/attribute — every route then enforces it.

## That's it

Check out the docs for information on how to use the bundle! [Return to the index.](index.md)
