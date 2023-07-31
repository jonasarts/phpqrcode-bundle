<?php

declare(strict_types=1);

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
    private string $user = 'test';
    private string $pass = 'te$t';

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testGetQRCodePNGAction()
    {
        $client = static::createClient();

        //$crawler = $client->request('GET', '/qr/png', array(), array(), array('PHP_AUTH_USER' => $this->user, 'PHP_AUTH_PW' => $this->pass));
        ob_start();
        $crawler = $client->request('GET', '/qr/png', array(), array(), array());
        $response = $client->getResponse();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/png', $client->getResponse()->headers->get('Content-type'));

        //$crawler = $client->request('GET', '/qr/png?text=Test', array(), array(), array('PHP_AUTH_USER' => $this->user, 'PHP_AUTH_PW' => $this->pass));
        ob_start();
        $crawler = $client->request('GET', '/qr/png?text=Test', array(), array(), array());
        $response = $client->getResponse();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/png', $client->getResponse()->headers->get('Content-type'));
        //*/
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testGetQRCodePNGwValuesAction()
    {
        $client = static::createClient();

        //$crawler = $client->request('GET', '/qr/png/Q/4/3', array(), array(), array('PHP_AUTH_USER' => $this->user, 'PHP_AUTH_PW' => $this->pass));
        ob_start();
        $crawler = $client->request('GET', '/qr/png/Q/4/3', array(), array(), array());
        $response = $client->getResponse();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/png', $client->getResponse()->headers->get('Content-type'));

        //$crawler = $client->request('GET', '/qr/png/Q/4/3?text=Test', array(), array(), array('PHP_AUTH_USER' => $this->user, 'PHP_AUTH_PW' => $this->pass));
        ob_start();
        $crawler = $client->request('GET', '/qr/png/Q/4/3?text=Test', array(), array(), array());
        $response = $client->getResponse();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/png', $client->getResponse()->headers->get('Content-type'));
        //*/
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testGetQRCodeSVGAction()
    {
        $client = static::createClient();

        ob_start();
        $crawler = $client->request('GET', '/qr/svg', array(), array(), array());
        $response = $client->getResponse();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/svg+xml', $client->getResponse()->headers->get('Content-Type'));

        ob_start();
        $crawler = $client->request('GET', '/qr/svg?text=Test', array(), array(), array());
        $response = $client->getResponse();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/svg+xml', $client->getResponse()->headers->get('Content-Type'));
        //*/
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testGetQRCodeSVGwValuesAction()
    {
        $client = static::createClient();

        ob_start();
        $crawler = $client->request('GET', '/qr/svg/Q/4/3', array(), array(), array());
        $response = $client->getResponse();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/svg+xml', $client->getResponse()->headers->get('Content-Type'));

        ob_start();
        $crawler = $client->request('GET', '/qr/svg/Q/4/3?text=Test', array(), array(), array());
        $response = $client->getResponse();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('image/svg+xml', $client->getResponse()->headers->get('Content-Type'));
        //*/
    }
}
