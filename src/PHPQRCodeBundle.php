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

namespace jonasarts\Bundle\PHPQRCodeBundle;

use Override;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * PHP QR Code Bundle.
 *
 * Thin Symfony wrapper around chillerlan/php-qrcode.
 */
class PHPQRCodeBundle extends AbstractBundle
{
    /**
     * Pin the config/extension alias to "phpqrcode".
     *
     * AbstractBundle would otherwise derive "phpqr_code" from the class name,
     * which would break the historical `phpqrcode:` configuration key.
     */
    protected string $extensionAlias = 'phpqrcode';

    /**
     * Keep the historical root path ("@PHPQRCodeBundle/src/..." in routing).
     */
    #[Override]
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('default')
                    ->addDefaultsIfNotSet()
                    ->children()
                        // level L M Q H
                        ->enumNode('level')
                            ->values(['L', 'M', 'Q', 'H'])
                            ->defaultValue('Q')
                        ->end()
                        // size 1 - 10
                        ->integerNode('size')
                            ->min(1)
                            ->max(10)
                            ->defaultValue(4)
                        ->end()
                        // margin (quiet zone modules)
                        ->integerNode('margin')
                            ->min(0)
                            ->defaultValue(3)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('limits')
                    ->addDefaultsIfNotSet()
                    ->children()
                        // hard cap for the ?text= payload (DoS protection)
                        ->integerNode('max_text_length')
                            ->min(1)
                            ->defaultValue(1500)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('access')
                    ->addDefaultsIfNotSet()
                    ->children()
                        // optional role/attribute checked on every route; null = no check
                        ->scalarNode('role')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param array{
     *     default: array{level: string, size: int, margin: int},
     *     limits: array{max_text_length: int},
     *     access: array{role: string|null},
     * } $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->setParameter('phpqrcode.default.level', $config['default']['level']);
        $builder->setParameter('phpqrcode.default.size', $config['default']['size']);
        $builder->setParameter('phpqrcode.default.margin', $config['default']['margin']);
        $builder->setParameter('phpqrcode.limits.max_text_length', $config['limits']['max_text_length']);
        $builder->setParameter('phpqrcode.access.role', $config['access']['role']);

        $container->import(__DIR__.'/Resources/config/services.yaml');
    }
}
