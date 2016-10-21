<?php

/*
 * This file is part of the GoogleAuthenticator bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PHPQRCodeBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use jonasarts\Bundle\PHPQRCodeBundle\PHPQRCodeController;

class PHPQRCodeControllerTest extends WebTestCase
{
    private $user = 'test';
    private $pass = 'te$t';

    /*
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
    }
    */

    public function testGetQRCodePNGAction()
    {
        $client = static::createClient();

        /* png output not yet testable
        $crawler = $client->request('GET', '/qr/png', array(), array(), array('PHP_AUTH_USER' => $this->user, 'PHP_AUTH_PW' => $this->pass));
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'image/png'), $response->headers);

        $crawler = $client->request('GET', '/qr/png?text=Test', array(), array(), array('PHP_AUTH_USER' => $this->user, 'PHP_AUTH_PW' => $this->pass));
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'image/png'), $response->headers);
        */
    }

    public function testGetQRCodePNGwDefaultsAction()
    {
        $client = static::createClient();

        /* png output not yet testable
        $crawler = $client->request('GET', '/qr/png/Q/4/3', array(), array(), array('PHP_AUTH_USER' => $this->user, 'PHP_AUTH_PW' => $this->pass));
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'image/png'), $response->headers);

        $crawler = $client->request('GET', '/qr/png/Q/4/3?text=Test', array(), array(), array('PHP_AUTH_USER' => $this->user, 'PHP_AUTH_PW' => $this->pass));
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'image/png'), $response->headers);
        */
    }
}
