<?php
/**
 * Created by mcfedr on 27/06/15 15:28
 */

namespace Ekreative\RedmineLoginBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = $this->createClient();
        $client->request('POST', '/login', [], [], [], json_encode([
            'login' => [
                'username' => 'username',
                'password' => 'password'
            ]
        ]));

        $response = $client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);
    }
}
