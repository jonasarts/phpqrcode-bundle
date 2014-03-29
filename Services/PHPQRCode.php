<?php

/*
 * This file is part of the PHP QR Code bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PHPQRCodeBundle\Services;

require_once __DIR__ . '/../lib/qrlib.php'; 

/**
 * PHP QR Code Service
 */
class PHPQRCode
{
    public function __construct()
    {

    }

    /**
     * 
     * @param string  $text
     * @param boolean $outfile
     * @param string  $level
     * @param integer $size
     * @param integer $margin
     * @param boolean $saveandprint
     * 
     * QRcode params:
     * $text,
     * $outfile = false,
     * $level = QR_ECLEVEL_L,
     * $size = 3,
     * $margin = 4,
     * $saveandprint = false
     */
    public function generatePNG($text, $outfile = false, $level = 'L', $size = 3, $margin = 4, $saveandprint = false)
    {
        \QRcode::png($text, $outfile, $level, $size, $margin, $saveandprint);

        exit(0);
    }
}
