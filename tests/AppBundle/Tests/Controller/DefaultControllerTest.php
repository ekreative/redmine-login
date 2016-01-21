<?php

namespace AppBundle\Tests\Controller;

use Ekreative\RedmineLoginBundle\Tests\RedmineTestCase;

class DefaultControllerTest extends RedmineTestCase
{
    public function testIndex()
    {
        $this->setUpUserMock();
        $this->client->disableReboot();
        $this->client->request('POST', '/login_check', [
            'login' => [
                'username' => 'user',
                'password' => 'pass'
            ]
        ]);
        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Homepage")')->count() > 0);
    }
}
