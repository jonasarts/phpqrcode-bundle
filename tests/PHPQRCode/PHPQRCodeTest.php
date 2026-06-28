<?php

declare(strict_types=1);

/*
 * This file is part of the PHP QR Code bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PHPQRCodeBundle\Tests\PHPQRCode;

use chillerlan\QRCode\QRCode;
use GdImage;
use jonasarts\Bundle\PHPQRCodeBundle\PHPQRCode\PHPQRCode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

#[CoversClass(PHPQRCode::class)]
final class PHPQRCodeTest extends TestCase
{
    private PHPQRCode $service;

    protected function setUp(): void
    {
        $this->service = new PHPQRCode();
    }

    public function testPngIsAValidImageWithCacheHeaders(): void
    {
        $response = $this->service->generatePNG('content check', 'M', 4, 2);
        $body = (string) $response->getContent();

        self::assertSame('image/png', $response->headers->get('Content-Type'));
        self::assertStringStartsWith("\x89PNG\r\n\x1a\n", $body, 'PNG magic bytes');
        self::assertGreaterThan(0, \strlen($body));
        self::assertTrue($response->headers->hasCacheControlDirective('public'));
        self::assertNotNull($response->getEtag());
    }

    public function testSvgIsWellFormedMarkup(): void
    {
        $response = $this->service->generateSVG('content check', 'M', 4, 2);
        $body = (string) $response->getContent();

        self::assertSame('image/svg+xml', $response->headers->get('Content-Type'));
        self::assertStringContainsString('<svg', $body);
        self::assertStringContainsString('</svg>', $body);
        // must parse as XML
        self::assertInstanceOf(SimpleXMLElement::class, simplexml_load_string($body));
    }

    /**
     * The decoded QR payload must equal the input (real content check, not just HTTP 200).
     */
    public function testPngEncodesTheInputText(): void
    {
        $input = 'https://www.jonasarts.com/?q=42';
        $response = $this->service->generatePNG($input, 'H', 5, 4);

        $result = new QRCode()->readFromBlob((string) $response->getContent());

        self::assertSame($input, (string) $result->data);
    }

    /**
     * Regression test for the historical color bug: custom colors are now wired into chillerlan.
     */
    public function testPngHonorsCustomColors(): void
    {
        // red foreground on white background
        $response = $this->service->generatePNG('colors', 'L', 6, 1, 0xFFFFFF, 0xFF0000);

        $image = imagecreatefromstring((string) $response->getContent());
        self::assertNotFalse($image);

        [$hasRed, $hasWhite, $hasBlack] = $this->scanColors($image);
        imagedestroy($image);

        self::assertTrue($hasRed, 'foreground modules should be red');
        self::assertTrue($hasWhite, 'background should be white');
        self::assertFalse($hasBlack, 'no default-black modules should remain');
    }

    public function testSvgHonorsCustomColors(): void
    {
        $response = $this->service->generateSVG('colors', 'L', 6, 1, 0xFFFFFF, 0xFF0000);
        $body = (string) $response->getContent();

        self::assertStringContainsString('#ff0000', $body, 'foreground fill color present');
        self::assertStringNotContainsString('#000000', $body, 'no default-black fill remaining');
    }

    /**
     * Stronger colour proof: a non-default background (so white can't pass by coincidence)
     * with a non-default, non-black foreground. Both must be wired independently.
     */
    public function testPngHonorsNonDefaultBackgroundAndForeground(): void
    {
        // blue foreground (0x0000FF) on a yellow background (0xFFFF00)
        $response = $this->service->generatePNG('bg+fg', 'L', 6, 1, 0xFFFF00, 0x0000FF);

        $image = imagecreatefromstring((string) $response->getContent());
        self::assertNotFalse($image);

        $colors = $this->distinctColors($image);
        imagedestroy($image);

        self::assertContains('0,0,255', $colors, 'foreground should be blue');
        self::assertContains('255,255,0', $colors, 'background should be yellow');
        self::assertNotContains('0,0,0', $colors, 'no default-black pixels');
        self::assertNotContains('255,255,255', $colors, 'no default-white pixels');
    }

    public function testSvgHonorsNonDefaultBackgroundAndForeground(): void
    {
        $response = $this->service->generateSVG('bg+fg', 'L', 6, 1, 0xFFFF00, 0x0000FF);
        $body = (string) $response->getContent();

        self::assertStringContainsString('#0000ff', $body, 'foreground fill present');
        self::assertStringContainsString('#ffff00', $body, 'background fill present');
    }

    /**
     * Without colour arguments the output must stay the conventional black-on-white.
     */
    public function testPngDefaultsToBlackOnWhite(): void
    {
        $response = $this->service->generatePNG('default colors', 'L', 6, 1);

        $image = imagecreatefromstring((string) $response->getContent());
        self::assertNotFalse($image);

        [$hasRed, $hasWhite, $hasBlack] = $this->scanColors($image);
        imagedestroy($image);

        self::assertTrue($hasBlack, 'default foreground should be black');
        self::assertTrue($hasWhite, 'default background should be white');
        self::assertFalse($hasRed);
    }

    /**
     * A higher ECC level changes the symbol -> proves the level is actually applied.
     */
    public function testEccLevelAffectsOutput(): void
    {
        $low = (string) $this->service->generatePNG('ecc level', 'L', 4, 2)->getContent();
        $high = (string) $this->service->generatePNG('ecc level', 'H', 4, 2)->getContent();

        self::assertNotSame($low, $high);
    }

    public function testEccLevelIsCaseInsensitive(): void
    {
        $upper = (string) $this->service->generatePNG('case', 'Q', 4, 2)->getContent();
        $lower = (string) $this->service->generatePNG('case', 'q', 4, 2)->getContent();

        self::assertSame($upper, $lower);
    }

    public function testInvalidEccLevelFallsBackToL(): void
    {
        $explicitL = (string) $this->service->generatePNG('fallback', 'L', 4, 2)->getContent();
        $invalid = (string) $this->service->generatePNG('fallback', 'Z', 4, 2)->getContent();

        self::assertSame($explicitL, $invalid);
    }

    /**
     * margin = 0 exercises the addQuietzone=false branch and must still produce a readable code.
     */
    public function testZeroMarginRendersDecodableCode(): void
    {
        $input = 'no-quiet-zone';
        $response = $this->service->generatePNG($input, 'M', 5, 0);

        self::assertStringStartsWith("\x89PNG", (string) $response->getContent());

        $result = new QRCode()->readFromBlob((string) $response->getContent());
        self::assertSame($input, (string) $result->data);
    }

    public function testEtagIsDeterministicAndInputDependent(): void
    {
        $a = $this->service->generatePNG('same input', 'L', 4, 2);
        $b = $this->service->generatePNG('same input', 'L', 4, 2);
        $c = $this->service->generatePNG('other input', 'L', 4, 2);

        self::assertSame($a->getEtag(), $b->getEtag(), 'same input -> same ETag');
        self::assertNotSame($a->getEtag(), $c->getEtag(), 'different input -> different ETag');
    }

    /**
     * @return array{0: bool, 1: bool, 2: bool} [hasRed, hasWhite, hasBlack]
     */
    private function scanColors(GdImage $image): array
    {
        $w = imagesx($image);
        $h = imagesy($image);
        $hasRed = false;
        $hasWhite = false;
        $hasBlack = false;

        for ($y = 0; $y < $h; ++$y) {
            for ($x = 0; $x < $w; ++$x) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                if (255 === $r && 0 === $g && 0 === $b) {
                    $hasRed = true;
                } elseif (255 === $r && 255 === $g && 255 === $b) {
                    $hasWhite = true;
                } elseif (0 === $r && 0 === $g && 0 === $b) {
                    $hasBlack = true;
                }
            }
        }

        return [$hasRed, $hasWhite, $hasBlack];
    }

    /**
     * @return list<string> distinct "r,g,b" colour tuples present in the image
     */
    private function distinctColors(GdImage $image): array
    {
        $w = imagesx($image);
        $h = imagesy($image);
        $colors = [];

        for ($y = 0; $y < $h; ++$y) {
            for ($x = 0; $x < $w; ++$x) {
                $rgb = imagecolorat($image, $x, $y);
                $key = (($rgb >> 16) & 0xFF).','.(($rgb >> 8) & 0xFF).','.($rgb & 0xFF);
                $colors[$key] = true;
            }
        }

        return array_keys($colors);
    }
}
