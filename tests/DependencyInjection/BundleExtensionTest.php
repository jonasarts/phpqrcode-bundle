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

namespace jonasarts\Bundle\PHPQRCodeBundle\Tests\DependencyInjection;

use jonasarts\Bundle\PHPQRCodeBundle\Controller\PHPQRCodeController;
use jonasarts\Bundle\PHPQRCodeBundle\PHPQRCode\PHPQRCode;
use jonasarts\Bundle\PHPQRCodeBundle\PHPQRCode\PHPQRCodeInterface;
use jonasarts\Bundle\PHPQRCodeBundle\PHPQRCodeBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Exercises the bundle's configuration tree and service wiring (no Kernel).
 */
final class BundleExtensionTest extends TestCase
{
    /**
     * @param array<string, mixed> $config
     */
    private function load(array $config = []): ContainerBuilder
    {
        $container = new ContainerBuilder();
        // AbstractBundle's extension reads these kernel parameters when loading
        // (required on Symfony 7.0; defaulted on 8.x). Provide them so the bare
        // test container works across the whole supported Symfony range.
        $container->setParameter('kernel.environment', 'test');
        $container->setParameter('kernel.build_dir', sys_get_temp_dir());

        $extension = new PHPQRCodeBundle()->getContainerExtension();

        self::assertNotNull($extension);
        $extension->load([] === $config ? [] : [$config], $container);

        return $container;
    }

    public function testExtensionAliasIsPinnedToPhpqrcode(): void
    {
        $extension = new PHPQRCodeBundle()->getContainerExtension();

        self::assertNotNull($extension);
        self::assertSame('phpqrcode', $extension->getAlias());
    }

    public function testDefaultParametersAreApplied(): void
    {
        $container = $this->load();

        self::assertSame('Q', $container->getParameter('phpqrcode.default.level'));
        self::assertSame(4, $container->getParameter('phpqrcode.default.size'));
        self::assertSame(3, $container->getParameter('phpqrcode.default.margin'));
        self::assertSame(1500, $container->getParameter('phpqrcode.limits.max_text_length'));
        self::assertNull($container->getParameter('phpqrcode.access.role'));
    }

    public function testCustomConfigurationOverridesDefaults(): void
    {
        $container = $this->load([
            'default' => ['level' => 'H', 'size' => 8, 'margin' => 0],
            'limits' => ['max_text_length' => 500],
            'access' => ['role' => 'ROLE_USER'],
        ]);

        self::assertSame('H', $container->getParameter('phpqrcode.default.level'));
        self::assertSame(8, $container->getParameter('phpqrcode.default.size'));
        self::assertSame(0, $container->getParameter('phpqrcode.default.margin'));
        self::assertSame(500, $container->getParameter('phpqrcode.limits.max_text_length'));
        self::assertSame('ROLE_USER', $container->getParameter('phpqrcode.access.role'));
    }

    public function testServiceAliasAndControllerAreRegistered(): void
    {
        $container = $this->load();

        self::assertTrue($container->hasDefinition(PHPQRCode::class), 'service is registered');
        self::assertTrue($container->hasAlias(PHPQRCodeInterface::class), 'interface alias is registered');
        self::assertTrue($container->hasDefinition(PHPQRCodeController::class), 'controller is registered');
    }
}
