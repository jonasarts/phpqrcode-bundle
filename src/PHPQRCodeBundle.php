<?php

declare(strict_types=1);

namespace jonasarts\Bundle\PHPQRCodeBundle;

use jonasarts\Bundle\PHPQRCodeBundle\DependencyInjection\PHPQRCodeExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PHPQRCodeBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new PHPQRCodeExtension();
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
