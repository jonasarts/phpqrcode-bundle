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

namespace jonasarts\Bundle\PHPQRCodeBundle\PHPQRCode;

use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

/**
 * PHP QR Code service.
 *
 * Thin wrapper around chillerlan/php-qrcode (v6) that renders a QR code
 * straight into an HTTP Response.
 */
class PHPQRCode implements PHPQRCodeInterface
{
    /**
     * Module types that represent a "dark" (foreground) pixel.
     */
    private const array DARK_MODULES = [
        QRMatrix::M_DARKMODULE,
        QRMatrix::M_DATA_DARK,
        QRMatrix::M_FINDER_DARK,
        QRMatrix::M_SEPARATOR_DARK,
        QRMatrix::M_ALIGNMENT_DARK,
        QRMatrix::M_TIMING_DARK,
        QRMatrix::M_FORMAT_DARK,
        QRMatrix::M_VERSION_DARK,
        QRMatrix::M_QUIETZONE_DARK,
        QRMatrix::M_LOGO_DARK,
        QRMatrix::M_FINDER_DOT,
    ];

    /**
     * Module types that represent a "light" (background) pixel.
     */
    private const array LIGHT_MODULES = [
        QRMatrix::M_DARKMODULE_LIGHT,
        QRMatrix::M_DATA,
        QRMatrix::M_FINDER,
        QRMatrix::M_SEPARATOR,
        QRMatrix::M_ALIGNMENT,
        QRMatrix::M_TIMING,
        QRMatrix::M_FORMAT,
        QRMatrix::M_VERSION,
        QRMatrix::M_QUIETZONE,
        QRMatrix::M_LOGO,
        QRMatrix::M_FINDER_DOT_LIGHT,
    ];

    public function generatePNG(string $text, string $level = 'L', int $size = 3, int $margin = 4, int $back_color = 0xFFFFFF, int $fore_color = 0x000000): Response
    {
        $options = $this->buildOptions(QRGdImagePNG::class, $level, $size, $margin, $back_color, $fore_color, false);
        // PNG also needs an explicit background color
        $options['bgColor'] = $this->hexToRgb($back_color);
        $options['imageTransparent'] = false;

        $body = new QRCode(new QROptions($options))->render($text);

        return $this->toResponse($body, 'image/png', 'qrcode.png');
    }

    public function generateSVG(string $text, string $level = 'L', int $size = 3, int $margin = 4, int $back_color = 0xFFFFFF, int $fore_color = 0x000000): Response
    {
        $options = $this->buildOptions(QRMarkupSVG::class, $level, $size, $margin, $back_color, $fore_color, true);
        // connect the dark module paths for cleaner SVG markup
        $options['connectPaths'] = true;

        $body = new QRCode(new QROptions($options))->render($text);

        return $this->toResponse($body, 'image/svg+xml', 'qrcode.svg');
    }

    /**
     * Shared options builder for every output type.
     *
     * @param class-string $outputInterface FQCN of the chillerlan output class
     * @param bool         $markup          true for markup outputs (hex colors), false for GD (RGB arrays)
     *
     * @return array<string, mixed>
     */
    private function buildOptions(string $outputInterface, string $level, int $size, int $margin, int $back_color, int $fore_color, bool $markup): array
    {
        return [
            'outputInterface' => $outputInterface,
            'outputBase64' => false, // return raw bytes, not a data URI
            'eccLevel' => $this->normalizeLevel($level), // v6 accepts L/M/Q/H directly
            'scale' => $size,
            'addQuietzone' => $margin > 0,
            'quietzoneSize' => max(0, $margin),
            'moduleValues' => $this->moduleValues($back_color, $fore_color, $markup),
        ];
    }

    /**
     * Normalize the ECC level to one of L/M/Q/H (defaults to L).
     */
    private function normalizeLevel(string $level): string
    {
        $level = strtoupper($level);

        return \in_array($level, ['L', 'M', 'Q', 'H'], true) ? $level : 'L';
    }

    /**
     * Build the chillerlan moduleValues map from the requested colors.
     *
     * @return array<int, array{0: int, 1: int, 2: int}|string>
     */
    private function moduleValues(int $back_color, int $fore_color, bool $markup): array
    {
        $back = $markup ? $this->hexToString($back_color) : $this->hexToRgb($back_color);
        $fore = $markup ? $this->hexToString($fore_color) : $this->hexToRgb($fore_color);

        $values = [];

        foreach (self::DARK_MODULES as $type) {
            $values[$type] = $fore;
        }

        foreach (self::LIGHT_MODULES as $type) {
            $values[$type] = $back;
        }

        return $values;
    }

    /**
     * Convert a 0xRRGGBB integer to a [R, G, B] array (0-255), for GD/EPS/FPDF output.
     *
     * @return array{0: int, 1: int, 2: int}
     */
    private function hexToRgb(int $color): array
    {
        return [
            ($color >> 16) & 0xFF,
            ($color >> 8) & 0xFF,
            $color & 0xFF,
        ];
    }

    /**
     * Convert a 0xRRGGBB integer to a "#rrggbb" string, for markup output (SVG/HTML/Imagick).
     */
    private function hexToString(int $color): string
    {
        return sprintf('#%06x', $color & 0xFFFFFF);
    }

    /**
     * Wrap a rendered QR code body into a cacheable inline HTTP Response.
     */
    private function toResponse(string $body, string $contentType, string $filename): Response
    {
        $response = new Response($body, Response::HTTP_OK, ['Content-Type' => $contentType]);

        $disposition = HeaderUtils::makeDisposition(HeaderUtils::DISPOSITION_INLINE, $filename);
        $response->headers->set('Content-Disposition', $disposition);

        // QR output is deterministic for a given input -> safe to cache
        $response->setPublic();
        $response->setMaxAge(86400);
        $response->setEtag(md5($body));

        return $response;
    }
}
