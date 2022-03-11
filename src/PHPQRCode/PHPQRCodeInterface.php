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

use Symfony\Component\HttpFoundation\Response;

interface PHPQRCodeInterface
{
    function generatePNG(string $text, string $level = 'L', int $size = 3, int $margin = 4, bool $saveandprint = false, int $back_color = 0xFFFFFF, int $fore_color = 0x000000): Response;
    function generateSVG(string $text, string $level = 'L', int $size = 3, int $margin = 4, bool $saveandprint = false, int $back_color = 0xFFFFFF, int $fore_color = 0x000000): Response;
}
