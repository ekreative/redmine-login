<?php
/**
 * Created by mcfedr on 27/06/15 12:23
 */

namespace Ekreative\RedmineLoginBundle\Security;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RedmineUserProvider implements UserProviderInterface
{
    /**
     * @var Client
     */
    private $redmine;

    public function __construct(Client $redmine)
    {
        $this->redmine = $redmine;
    }

    public function getUserForApiKey($apiKey)
    {
        try {
            $response = $this->redmine->get('users/current.json', [
                'headers' => [
                    'X-Redmine-API-Key' => $apiKey
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new AuthenticationException('Invalid credentials');
            }

            return new RedmineUser(json_decode($response->getBody(), true)['user']);
        }
        catch (RequestException $e) {
            throw new AuthenticationException('Invalid credentials');
        }
    }

    public function getUserForUsernamePassword($username, $password)
    {
        try {
            $response = $this->redmine->get('users/current.json', [
                'auth' => [$username, $password]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new AuthenticationException('Invalid credentials');
            }

            return new RedmineUser(json_decode($response->getBody(), true)['user']);
        }
        catch (RequestException $e) {
            throw new AuthenticationException('Invalid credentials');
        }
    }

    public function loadUserByUsername($username)
    {
        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof RedmineUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $user;
    }

    public function supportsClass($class)
    {
        return $class == RedmineUser::class;
    }
}
