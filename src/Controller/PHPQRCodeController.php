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

namespace jonasarts\Bundle\PHPQRCodeBundle\Controller;

use jonasarts\Bundle\PHPQRCodeBundle\PHPQRCode\PHPQRCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route("/qr")
 */
#[Route('/qr')]
class PHPQRCodeController extends AbstractController
{
    /**
     * @var PHPQRCode|null
     */
    private ?PHPQRCode $qr = null;

    /**
     * Constructor
     */
    function __construct()
    {
         //parent::__construct();

         $this->qr = new PHPQRCode();
    }

    /**
     * Get QR Code service
     *
     * @return PHPQRCode
     */
    private function getQR(): PHPQRCode
    {
        return $this->qr;
    }

    /**
     *
     * @param string  $text
     * @param string  $level
     * @param int $size
     * @param int $margin
     *
     * @return Response
     */
    private function getQRCodePNG(string $text, string $level, int $size, int $margin): Response
    {
        //QRcode::png('PHP QR Code :)', 'test.png', QR_ECLEVEL_L, 4, 2);
        //QRcode::png('Testing', false, 'Q', 4, 3);

        return $this->getQR()->generatePNG($text, $level, $size, $margin);
    }

    /**
     *
     * @param string  $text
     * @param string  $level
     * @param int $size
     * @param int $margin
     *
     * @return Response
     */
    private function getQRCodeSVG(string $text, string $level, int $size, int $margin): Response
    {
        //QRcode::svg('PHP QR Code :)', 'id-of-svg', false, QR_ECLEVEL_L, 250);

        return $this->getQR()->generateSVG($text, $level, $size, $margin);
    }

    /**
     *
     * @Route("/test", name="qrcode_test")
     *
     * @return Response
     */
    #[Route('/test', name: 'qrcode_test')]
    public function testAction(): Response
    {
        return $this->getQR()->generatePNG("test", 'Q', 4, 3);
    }

    /**
     *
     * @Route("/png/{level}/{size}/{margin}", name="qrcode_png")
     *
     * @param Request $request
     * @param string $level
     * @param int $size
     * @param int $margin
     * @return Response
     */
    #[Route('/png/{level}/{size}/{margin}', name: 'qrcode_png')]
    public function getQRCodePNGAction(Request $request, string $level, int $size, int $margin): Response
    {
        $text = 'getQRCodePNGAction has no text content';

        if ($request->query->has('text')) {
            $text = $request->query->get('text');
        }

        if (trim($text) == '') {
            $text = 'EMPTY';
        }

        return $this->getQRCodePNG($text, $level, $size, $margin);
    }

    /**
     *
     * @Route("/png", name="qrcode_png_default")
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/png', name: 'qrcode_png_default')]
    public function getQRCodePNGwDefaultsAction(Request $request): Response
    {
        $text = 'getQRCodePNGwDefaultsAction has no text content';

        if ($request->query->has('text')) {
            $text = $request->query->get('text');
        }

        if (trim($text) == '') {
            $text = 'EMPTY';
        }

        $level = $this->getParameter('phpqrcode.default.level');
        $size = intval($this->getParameter('phpqrcode.default.size'));
        $margin = intval($this->getParameter('phpqrcode.default.margin'));

        return $this->getQRCodePNG($text, $level, $size, $margin);
    }

    /**
     *
     * @Route("/svg/{level}/{size}/{margin}", name="qrcode_svg")
     *
     * @param Request $request
     * @param string $level
     * @param int $size
     * @param int $margin
     * @return Response
     */
    #[Route('/svg/{level}/{size}/{margin}', name: 'qrcode_svg')]
    public function getQRCodeSVGAction(Request $request, string $level, int $size, int $margin): Response
    {
        $text = 'getQRCodeSVGAction has no text content';

        if ($request->query->has('text')) {
            $text = $request->query->get('text');
        }

        if (trim($text) == '') {
            $text = 'EMPTY';
        }

        return $this->getQRCodeSVG($text, $level, $size, $margin);
    }

    /**
     *
     * @Route("/svg", name="qrcode_svg_default")
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/svg', name: 'qrcode_svg_default')]
    public function getQRCodeSVGwDefaultsAction(Request $request): Response
    {
        $text = 'getQRCodeSVGwDefaultsAction has no text content';

        if ($request->query->has('text')) {
            $text = $request->query->get('text');
        }

        if (trim($text) == '') {
            $text = 'EMPTY';
        }

        $level = $this->getParameter('phpqrcode.default.level');
        $size = intval($this->getParameter('phpqrcode.default.size'));
        $margin = intval($this->getParameter('phpqrcode.default.margin'));

        return $this->getQRCodeSVG($text, $level, $size, $margin);
    }
}
