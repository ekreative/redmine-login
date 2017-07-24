<?php
/**
 * Created by mcfedr on 27/06/15 15:28
 */

namespace Ekreative\RedmineLoginBundle\Tests;

use AppBundle\Security\CustomUser;

class LoginControllerTest extends RedmineTestCase
{
    public function testApiLogin()
    {
        $this->setUpUserMock();
        $this->client->request('POST', '/login', [], [], [], json_encode([
            'login' => [
                'username' => 'user',
                'password' => 'pass'
            ]
        ]));

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['user']['admin']);
    }

    public function testHiddenEmailApiLogin()
    {
        $this->setUpUserHiddenEmailMock();
        $this->client->request('POST', '/login', [], [], [], json_encode([
            'login' => [
                'username' => 'user',
                'password' => 'pass'
            ]
        ]));

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertFalse($data['user']['admin']);
        $this->assertEquals($data['user']['email'], null);
    }

    public function testApiAdminLogin()
    {
        $this->setUpAdminMock();
        $this->client->request('POST', '/login', [], [], [], json_encode([
            'login' => [
                'username' => 'admin',
                'password' => 'pass'
            ]
        ]));

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertTrue($data['user']['admin']);
    }

    public function testApiFailLogin()
    {
        $this->setUpNoUserMock();
        $this->client->request('POST', '/login', [], [], [], json_encode([
            'login' => [
                'username' => 'user',
                'password' => 'pass'
            ]
        ]));

        $response = $this->client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testApiKeyLogin()
    {
        $this->setUpUserMock();

        $this->client->request('GET', '/', [], [], ['HTTP_X-API-Key' => 'key']);

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertInstanceOf(CustomUser::class, $this->client->getContainer()->get('security.token_storage')->getToken()->getUser());
        $this->assertTrue($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_REDMINE'));
        $this->assertFalse($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_REDMINE_ADMIN'));
    }

    public function testApiKeyAdminLogin()
    {
        $this->setUpAdminMock();

        $this->client->request('GET', '/', [], [], ['HTTP_X-API-Key' => 'key']);

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertInstanceOf(CustomUser::class, $this->client->getContainer()->get('security.token_storage')->getToken()->getUser());
        $this->assertTrue($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_REDMINE'));
        $this->assertTrue($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_REDMINE_ADMIN'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException
     */
    public function testApiNoKeyLogin()
    {
        $this->client->request('GET', '/', [], [], ['HTTP_X-API-Key' => 'key']);

        $response = $this->client->getResponse();
        $this->assertEquals(403, $response->getStatusCode());

        $this->assertNull($this->client->getContainer()->get('security.token_storage')->getToken());
        $this->assertFalse($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_REDMINE'));
    }

    public function testNothingRedirectLogin()
    {
        $this->client->request('GET', '/');

        $response = $this->client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/login', $response->headers->get('location'));

        $this->assertFalse($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_REDMINE'));
    }

    public function testFormLoginUser()
    {
        $this->setUpUserMock();
        $this->client->request('POST', '/login_check', [
            'login' => [
                'username' => 'user',
                'password' => 'pass'
            ]
        ]);
        $response = $this->client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/', $response->headers->get('location'));
        $this->assertInstanceOf(CustomUser::class, $this->client->getContainer()->get('security.token_storage')->getToken()->getUser());
        $this->assertTrue($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_REDMINE'));
        $this->assertFalse($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_REDMINE_ADMIN'));
    }

    public function testFormLoginAdmin()
    {
        $this->setUpAdminMock();
        $this->client->request('POST', '/login_check', [
            'login' => [
                'username' => 'user',
                'password' => 'pass'
            ]
        ]);
        $response = $this->client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/', $response->headers->get('location'));
        $this->assertInstanceOf(CustomUser::class, $this->client->getContainer()->get('security.token_storage')->getToken()->getUser());
        $this->assertTrue($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_REDMINE'));
        $this->assertTrue($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_REDMINE_ADMIN'));
    }

    public function testFormLoginNoUser()
    {
        $this->setUpNoUserMock();
        $this->client->request('POST', '/login_check', [
            'login' => [
                'username' => 'user',
                'password' => 'pass'
            ]
        ]);
        $response = $this->client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/login', $response->headers->get('location'));
    }
}
