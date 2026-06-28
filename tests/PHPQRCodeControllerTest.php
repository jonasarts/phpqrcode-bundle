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

use Override;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PHPQRCodeControllerTest extends WebTestCase
{
    /**
     * Booting the kernel registers an error/exception handler that the framework
     * does not always restore; drop it so PHPUnit does not flag the test as risky
     * for leaking a handler.
     */
    #[Override]
    protected function tearDown(): void
    {
        restore_exception_handler();

        parent::tearDown();
    }

    private function browser(): KernelBrowser
    {
        return self::createClient();
    }

    public function testPngDefaultRoute(): void
    {
        $client = $this->browser();
        $client->request('GET', '/qr/png?text=Test');

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('image/png', $response->headers->get('Content-Type'));
        self::assertStringStartsWith("\x89PNG", (string) $response->getContent());
        self::assertGreaterThan(0, \strlen((string) $response->getContent()));
        self::assertTrue($response->headers->hasCacheControlDirective('public'));
    }

    public function testPngParameterizedRoute(): void
    {
        $client = $this->browser();
        $client->request('GET', '/qr/png/Q/4/3?text=Test');

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('image/png', $response->headers->get('Content-Type'));
    }

    public function testSvgDefaultRoute(): void
    {
        $client = $this->browser();
        $client->request('GET', '/qr/svg?text=Test');

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('image/svg+xml', $response->headers->get('Content-Type'));
        self::assertStringContainsString('<svg', (string) $response->getContent());
    }

    public function testSvgParameterizedRoute(): void
    {
        $client = $this->browser();
        $client->request('GET', '/qr/svg/Q/4/3?text=Test');

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('image/svg+xml', $response->headers->get('Content-Type'));
    }

    public function testInvalidLevelReturns404(): void
    {
        $client = $this->browser();
        $client->request('GET', '/qr/png/X/4/3?text=Test');

        self::assertSame(404, $client->getResponse()->getStatusCode());
    }

    public function testOutOfRangeSizeReturns400(): void
    {
        $client = $this->browser();
        $client->request('GET', '/qr/png/Q/99/3?text=Test');

        self::assertSame(400, $client->getResponse()->getStatusCode());
    }

    public function testOverlongTextReturns400(): void
    {
        $client = $this->browser();
        $client->request('GET', '/qr/png?text='.str_repeat('A', 2000));

        self::assertSame(400, $client->getResponse()->getStatusCode());
    }
}
