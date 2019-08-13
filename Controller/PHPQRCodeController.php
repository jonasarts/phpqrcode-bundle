<?php

/*
 * This file is part of the PHP QR Code bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PHPQRCodeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/qr")
 */
class PHPQRCodeController extends AbstractController
{
    /**
     * @var PHPQRCode $service
     */
    private $qr = null;

    /**
     * Get QR Code service
     *
     * @return PHPQRCode
     */
    private function getQR()
    {
        if (is_null($this->qr)) {
            $this->qr = $this->container->get('phpqrcode');
        }

        return $this->qr;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        //
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
    private function getQRCodePNG($text, $level, $size, $margin)
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
    private function getQRCodeSVG($text, $level, $size, $margin)
    {
        //QRcode::svg('PHP QR Code :)', 'id-of-svg', false, QR_ECLEVEL_L, 250);

        return $this->getQR()->generateSVG($text, $level, 250, $size, $margin);
    }

    /**
     *
     * @Route("/png/{level}/{size}/{margin}", name="qrcode_png")
     *
     * @return Response
     */
    public function getQRCodePNGAction(Request $request, $level, $size, $margin)
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
     * @return Response
     */
    public function getQRCodePNGwDefaultsAction(Request $request)
    {
        $text = 'getQRCodePNGwDefaultsAction has no text content';

        if ($request->query->has('text')) {
            $text = $request->query->get('text');
        }

        if (trim($text) == '') {
            $text = 'EMPTY';
        }

        $level = $this->container->getParameter('phpqrcode.default.level');
        $size = $this->container->getParameter('phpqrcode.default.size');
        $margin = $this->container->getParameter('phpqrcode.default.margin');

        return $this->getQRCodePNG($text, $level, $size, $margin);
    }

    /**
     *
     * @Route("/svg/{level}/{size}/{margin}", name="qrcode_svg")
     *
     * @return Response
     */
    public function getQRCodeSVGAction(Request $request, $level, $size, $margin)
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
     * @return Response
     */
    public function getQRCodeSVGwDefaultsAction(Request $request)
    {
        $text = 'getQRCodeSVGwDefaultsAction has no text content';

        if ($request->query->has('text')) {
            $text = $request->query->get('text');
        }

        if (trim($text) == '') {
            $text = 'EMPTY';
        }

        $level = $this->container->getParameter('phpqrcode.default.level');
        $size = $this->container->getParameter('phpqrcode.default.size');
        $margin = $this->container->getParameter('phpqrcode.default.margin');

        return $this->getQRCodeSVG($text, $level, $size, $margin);
    }
}
