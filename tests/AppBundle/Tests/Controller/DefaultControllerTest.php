<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = $this->createClient();

        $client->request('GET', 'login');
        $client->request('POST', '/login_check', [
            'login' => [
                'username' => $client->getContainer()->getParameter('user_user'),
                'password' => $client->getContainer()->getParameter('user_pass')
            ]
        ]);

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Homepage")')->count() > 0);
    }
}
