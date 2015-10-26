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

    /**
     * @var RedmineUserFactory
     */
    private $userFactory;

    public function __construct(Client $redmine, RedmineUserFactory $userFactory)
    {
        $this->redmine = $redmine;
        $this->userFactory = $userFactory;
    }

    /**
     * @param string $apiKey
     * @return RedmineUser
     */
    public function getUserForApiKey($apiKey)
    {
        return $this->getUserWith([
            'headers' => [
                'X-Redmine-API-Key' => $apiKey
            ]
        ]);
    }

    /**
     * @param string $username
     * @param string $password
     * @return RedmineUser
     */
    public function getUserForUsernamePassword($username, $password)
    {
        return $this->getUserWith([
            'auth' => [$username, $password]
        ]);
    }

    /**
     * @param array $settings
     * @return RedmineUser
     */
    private function getUserWith(array $settings)
    {
        try {
            $response = $this->redmine->get('users/current.json', $settings);
            $data = json_decode($response->getBody(), true);

            if ($response->getStatusCode() != 200) {
                throw new AuthenticationException('Invalid credentials');
            }

            try {
                $this->redmine->get('groups.json', $settings);
                $isAdmin = true;
            }
            catch (RequestException $e) {
                $isAdmin = false;
            }

            return $this->userFactory->get($data['user'], $isAdmin);
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
        return is_subclass_of($class, RedmineUser::class);
    }
}
