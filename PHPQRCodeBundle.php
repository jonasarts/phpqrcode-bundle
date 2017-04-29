<?php

/*
 * This file is part of the PHP QR Code bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PHPQRCodeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use jonasarts\Bundle\PHPQRCodeBundle\DependencyInjection\PHPQRCodeExtension;

class PHPQRCodeBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new PHPQRCodeExtension();
    }
}
