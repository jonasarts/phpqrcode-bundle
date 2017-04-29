Setting up the bundle
=====================

## Install the bundle

Execute this console command in your project:

``` bash
$ composer require jonasarts/phpqrcode-bundle
```

## Enable the bundle

Enable the bundle in the kernel:

```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new jonasarts\Bundle\PHPQRCodeBundle\PHPQRCodeBundle(),
        );

        // ...
    }
}
```

And register the routes:

```yaml
#app/config/routing.yml
phpqrcode:
    resource: "@PHPQRCodeBundle/Controller/"
    type:     annotation
    prefix:   /
```

Optional, add a default configuration:

```yaml
# app/config/config.yml
phpqrcode:
    default:
        level: Q
        size: 4
        margin: 3

# ...
```

## That's it

Check out the docs for information on how to use the bundle! [Return to the index.](index.md)