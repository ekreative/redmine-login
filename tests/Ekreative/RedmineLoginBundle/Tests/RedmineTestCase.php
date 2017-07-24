<?php
/**
 * Created by mcfedr on 1/20/16 17:51
 */

namespace Ekreative\RedmineLoginBundle\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedmineTestCase extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    protected function setUpUserMock()
    {
        $redmineClient = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $redmineClient->expects($this->exactly(2))->method('__call')
            ->will($this->onConsecutiveCalls(
                $this->returnValue(new Response(200, [], json_encode(['user' => [
                    'id' => 1,
                    'login' => 'test',
                    'firstname' => 'firstname',
                    'lastname' => 'lastname',
                    'mail' => 'test@test.com',
                    'api_key' => 'test-api-key',
                    'created_on' => date('c'),
                    'last_login_on' => date('c')
                ]]))),
                $this->throwException($this->getMockBuilder(RequestException::class)->disableOriginalConstructor()->getMock()),
                $this->returnValue(new Response(200, [], json_encode([
                    'projects' => [
                        [
                            'id' => 1,
                            'name' => 'Project 1'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Project 2'
                        ]
                    ],
                    'total_count' => 2
                ])))
            ));
        // Its a bit crap, but I have to call this again here, so that after first run, where this is no cache, the
        // services that depend on this wont have been made yet
        $this->client = static::createClient();
        $this->client->getContainer()->set('ekreative_redmine_login.redmine', $redmineClient);
    }

    protected function setUpUserHiddenEmailMock()
    {
        $redmineClient = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $redmineClient->expects($this->exactly(2))->method('__call')
            ->will($this->onConsecutiveCalls(
                $this->returnValue(new Response(200, [], json_encode(['user' => [
                    'id' => 1,
                    'login' => 'test',
                    'firstname' => 'firstname',
                    'lastname' => 'lastname',
                    'api_key' => 'test-api-key',
                    'created_on' => date('c'),
                    'last_login_on' => date('c')
                ]]))),
                $this->throwException($this->getMockBuilder(RequestException::class)->disableOriginalConstructor()->getMock()),
                $this->returnValue(new Response(200, [], json_encode([
                    'projects' => [
                        [
                            'id' => 1,
                            'name' => 'Project 1'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Project 2'
                        ]
                    ],
                    'total_count' => 2
                ])))
            ));
        // Its a bit crap, but I have to call this again here, so that after first run, where this is no cache, the
        // services that depend on this wont have been made yet
        $this->client = static::createClient();
        $this->client->getContainer()->set('ekreative_redmine_login.redmine', $redmineClient);
    }

    protected function setUpAdminMock()
    {
        $redmineClient = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $redmineClient->expects($this->exactly(2))->method('__call')->willReturnOnConsecutiveCalls(
            new Response(200, [], json_encode(['user' => [
                'id' => 1,
                'login' => 'test',
                'firstname' => 'firstname',
                'lastname' => 'lastname',
                'mail' => 'test@test.com',
                'api_key' => 'test-api-key',
                'created_on' => date('c'),
                'last_login_on' => date('c')
            ]])),
            new Response(200)
        );
        $this->client->getContainer()->set('ekreative_redmine_login.redmine', $redmineClient);
    }

    protected function setUpNoUserMock()
    {
        $redmineClient = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $redmineClient->expects($this->exactly(1))->method('__call')->willReturnOnConsecutiveCalls(
            new Response(401)
        );
        $this->client->getContainer()->set('ekreative_redmine_login.redmine', $redmineClient);
    }
}
