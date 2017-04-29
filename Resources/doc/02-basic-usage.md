Using the bundle
================

The service class provides methods to generate the different QR Code images.

Retrieve the service like any other symfony service:

```php
    $qrc = $this->get('phpqrcode');
```

In the php code examples, ``$this`` referes to a controller.

```php
    // get the service
    $qrc = $this->get('phpqrcode');

    // output a custom png
    $qrc->getQRCodePNG($text, 'Q', 4, 3);

    // exit php
    exit(0);
```

As pure HTML tags
```html
    <img src='/qr/png?text=Test'>

    <img src='/qr/svg?text=Test'>
```

Use routes for Twig
```twig
    <img src="{{ path('qrcode_png_default', { 'text': 'Test' }) }}">

    <img src="{{ path('qrcode_svg_default', { 'text': 'Test' }) }}">
```

Eventually, you need to encode the text value:

```php
    echo "<img src='/qr/png?text=".urlencode("test@localhost")."'>\n";
```

[Return to the index.](index.md)
