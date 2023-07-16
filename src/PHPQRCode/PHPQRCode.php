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

use chillerlan\QRCode\{QRCode, QROptions};
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

//require_once __DIR__ . '/../../lib/qrlib.php';

/**
 * PHP QR Code Service
 */
class PHPQRCode implements PHPQRCodeInterface
{
    /**
     * Constructor
     */
    public function __construct()
    {
        //
    }

    /**
     *
     * @param string $text
     * -
     * @param string $level
     * @param int $size
     * @param int $margin
     * @param bool $saveandprint
     * @param hex $back_color
     * @param hex $fore_color
     *
     * QRcode params:
     * $text,
     * $outfile = false,
     * $level = QR_ECLEVEL_L,
     * $size = 3,
     * $margin = 4,
     * -$saveandprint = false-
     * $back_color = 0xFFFFFF
     * $fore_color = 0x000000
     *
     * @return Response
     */
    public function generatePNG(string $text, string $level = 'L', int $size = 3, int $margin = 4, int $back_color = 0xFFFFFF, int $fore_color = 0x000000): Response
    {
        $options = [
            'version'             => QRCode::VERSION_AUTO, // to avoid code length issues
	          'outputType'          => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'            => QRCode::ECC_L,
            'scale'               => $size,
            'addQuietzone'        => $margin > 0,
            'imageBase64'         => false,
	          'imageTransparent'    => false,
        ];

        // level = L M Q H
        switch ($level) {
            case 'M':
                //$level = QR_ECLEVEL_M;
                $options['eccLevel'] = QRCode::ECC_M;
                break;
            case 'Q':
                //$level = QR_ECLEVEL_Q;
                $options['eccLevel'] = QRCode::ECC_Q;
                break;
            case 'H':
                //$level = QR_ECLEVEL_H;
                $options['eccLevel'] = QRCode::ECC_H;
                break;
            default:
                //$level = QR_ECLEVEL_L;
                $options['eccLevel'] = QRCode::ECC_L;
        }

        /*
        // phpqrcode.php
        // QRcode
        // public static function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint=false, $back_color = 0xFFFFFF, $fore_color = 0x000000)

        ob_start();
        \QRcode::png($text, false, $level, $size, $margin, $saveandprint, $back_color, $fore_color);
        $imageString = ob_get_clean();
        */

        // invoke a fresh QRCode instance
        $qrcode = new QRCode(new QROptions($options));

        // and dump the output
        $imageString = $qrcode->render($text);

        // return as a http response

        $response = new Response($imageString);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            'qrcode.png'
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'image/png');

        return $response;
    }

    // generateJPG
    // generateGIF

    // generateText
    // generateJSON

    /**
     *
     * @param string $text
     * -
     * @param string $level
     * @param int $size
     * @param int $margin
     * @param bool $saveandprint
     * @param hex $back_color
     * @param hex $fore_color
     *
     * QRCode params:
     * $text,
     * $level = QR_ECLEVEL_L,
     * $size = 3,
     * $margin = 4,
     * -$saveandprint = false-
     * $back_color = 0xFFFFFF
     * $fore_color = 0x000000
     *
     * @return Response
     */
    public function generateSVG(string $text, string $level = 'L', int $size = 3, int $margin = 4, int $back_color = 0xFFFFFF, int $fore_color = 0x000000): Response
    {
        $options = [
            'version'             => QRCode::VERSION_AUTO, // to avoid code length issues
            'outputType'          => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'            => QRCode::ECC_L,
            'scale'               => $size,
            'addQuietzone'        => $margin > 0,
            'imageBase64'         => false,
            //'imageTransparent'    => false,
            'svgConnectPaths'     => true,
        ];

        // level = L M Q H
        switch ($level) {
            case 'M':
                //$level = QR_ECLEVEL_M;
                $options['eccLevel'] = QRCode::ECC_M;
                break;
            case 'Q':
                //$level = QR_ECLEVEL_Q;
                $options['eccLevel'] = QRCode::ECC_Q;
                break;
            case 'H':
                //$level = QR_ECLEVEL_H;
                $options['eccLevel'] = QRCode::ECC_H;
                break;
            default:
                //$level = QR_ECLEVEL_L;
                $options['eccLevel'] = QRCode::ECC_L;
        }

        /*
        // phpqrcode.php
        // QRcode
        // public static function svg($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint=false, $back_color = 0xFFFFFF, $fore_color = 0x000000)

        ob_start();
        \QRcode::svg($text, false, $level, $size, $margin, $saveandprint, $back_color, $fore_color);
        $imageString = ob_get_clean();
        */

        // invoke a fresh QRCode instance
        $qrcode = new QRCode(new QROptions($options));

        // and dump the output
        $imageString = $qrcode->render($text);

        // return as a http response

        $response = new Response($imageString);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            'qrcode.svg'
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'image/svg+xml');

        return $response;
    }
}
