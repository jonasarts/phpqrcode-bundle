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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/qr")
 */
class PHPQRCodeController extends Controller
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
        
    }

    /**
     * 
     * @param string  $text
     * @param string  $level
     * @param integer $size
     * @param integer $margin
     * @return PNG
     */
    public function getQRCodePNG($text, $level, $size, $margin)
    {
        //QRcode::png('PHP QR Code :)', 'test.png', 'L', 4, 2);
        //QRcode::png('Testing', false, 'Q', 4, 3);

        $this->getQR()->generatePNG($text, false, $level, $size, $margin);

        exit(0);
    }

    /**
     * 
     * @Route("/png/{level}/{size}/{margin}", name="qrcode_png")
     */
    public function getQRCodePNGAction($level, $size, $margin)
    {
        $text = 'getQRCodePNGAction has no text content';

        if ($this->getRequest()->query->has('text')) {
            $text = $this->getRequest()->query->get('text');
        }

        if (trim($text) == '') {
            $text = 'EMPTY';
        }
        
        $this->getQRCodePNG($text, $level, $size, $margin);
    }

    /**
     * 
     * @Route("/png", name="qrcode_png_default")
     */
    public function getQRCodePNGwDefaultsAction()
    {
        $text = 'getQRCodePNGwDefaultsAction has no text content';

        if ($this->getRequest()->query->has('text')) {
            $text = $this->getRequest()->query->get('text');
        }

        if (trim($text) == '') {
            $text = 'EMPTY';
        }

        $level = $this->container->getParameter('phpqrcode.default.level');
        $size = $this->container->getParameter('phpqrcode.default.size');
        $margin = $this->container->getParameter('phpqrcode.default.margin');

        $this->getQRCodePNG($text, $level, $size, $margin);
    }
}
