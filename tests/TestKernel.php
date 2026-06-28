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

namespace jonasarts\Bundle\PHPQRCodeBundle\Tests;

use jonasarts\Bundle\PHPQRCodeBundle\PHPQRCodeBundle;
use Override;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * Minimal kernel to exercise the bundle in functional (WebTestCase) tests.
 */
class TestKernel extends Kernel
{
    use MicroKernelTrait;

    #[Override]
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new PHPQRCodeBundle(),
        ];
    }

    private function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', [
            'secret' => 'phpqrcode-test',
            'test' => true,
            'http_method_override' => false,
            'php_errors' => ['log' => true],
        ]);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('@PHPQRCodeBundle/src/Controller/', 'attribute');
    }

    #[Override]
    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/phpqrcode-bundle/cache/'.$this->environment;
    }

    #[Override]
    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/phpqrcode-bundle/logs';
    }
}
