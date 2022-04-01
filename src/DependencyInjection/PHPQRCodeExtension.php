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

namespace jonasarts\Bundle\PHPQRCodeBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 */
class PHPQRCodeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        /* old
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // apply config default
        $container->setParameter('phpqrcode.default.level', $config['default']['level']);
        $container->setParameter('phpqrcode.default.size', $config['default']['size']);
        $container->setParameter('phpqrcode.default.margin', $config['default']['margin']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        */

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        // apply config default
        $container->setParameter('phpqrcode.default.level', $config['default']['level']);
        $container->setParameter('phpqrcode.default.size', $config['default']['size']);
        $container->setParameter('phpqrcode.default.margin', $config['default']['margin']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->addAnnotatedClassesToCompile([
            // you can define the fully qualified class names...
            'jonasarts\\Bundle\\PHPQRCodeBundle\\Controller\\PHPQRCodeController',
            // ...
      ]);
    }

    /**
     * The extension alias to override the default extension name (which is phpqr_code)
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'phpqrcode';
    }
}
