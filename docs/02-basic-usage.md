Using the bundle
================

The service class provides methods to generate the different QR Code images.

Use dependency injection (type-hint the interface) to retrieve the service
within a controller:

```php
    use jonasarts\Bundle\PHPQRCodeBundle\PHPQRCode\PHPQRCodeInterface;

    function someAction(PHPQRCodeInterface $qrc)
    {
        // use $qrc ...
        return $qrc->generatePNG("test", 'Q', 4, 3);
    }
```

Both methods accept optional background and foreground colors as `0xRRGGBB`
integers (these are now applied to the rendered code):

```php
    // red foreground on a white background
    $response = $qrc->generatePNG("custom test", 'Q', 4, 3, 0xFFFFFF, 0xFF0000);

    return $response;
```

Method signatures:

```php
    generatePNG(string $text, string $level = 'L', int $size = 3, int $margin = 4, int $back_color = 0xFFFFFF, int $fore_color = 0x000000): Response
    generateSVG(string $text, string $level = 'L', int $size = 3, int $margin = 4, int $back_color = 0xFFFFFF, int $fore_color = 0x000000): Response
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
