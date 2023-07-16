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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('phpqrcode');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('default')
                    ->addDefaultsIfNotSet()
                    ->children()
                        // level L M Q H
                        ->enumNode('level')
                            ->values(array('L', 'M', 'Q', 'H'))
                            ->defaultValue('Q')
                        ->end()
                        // size 1 - 10
                        ->integerNode('size')
                            ->min(1)
                            ->max(10)
                            ->defaultValue(4)
                        ->end()
                        // margin
                        ->integerNode('margin')
                            ->defaultValue(3)
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
