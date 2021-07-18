Using the bundle
================

The service class provides methods to generate the different QR Code images.

Use dependency injection to retrieve the service within a controller:

```php
    function someAction(PHPQRCode $qrc)
    {
        // use $qrc ...
        return $qrc->generatePNG("test", 'Q', 4, 3);
    }
```

```php
    $qrc = new \jonasarts\Bundle\PHPQRCodeBundle\PHPQRCode\PHPQRCode();

    // output a custom png
    $response = $qrc->generatePNG("custom test", 'Q', 4, 3);

    return $response;
```

As pure HTML tags (if routes are registered)
```html
    <img src='/qr/png?text=Test'>

    <img src='/qr/svg?text=Test'>
```

Use routes with Twig
```twig
    <img src="{{ path('qrcode_png_default', { 'text': 'Test' }) }}">

    <img src="{{ path('qrcode_svg_default', { 'text': 'Test' }) }}">
```

Eventually, you need to encode the text value:

```php
    echo "<img src='/qr/png?text=".urlencode("test@localhost")."'>\n";
```

[Return to the index.](index.md)
