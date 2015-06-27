<?php
/**
 * Created by mcfedr on 27/06/15 11:38
 */

namespace Ekreative\RedmineLoginBundle\Security;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class RedmineAuthenticator implements SimpleFormAuthenticatorInterface
{
    /**
     * @var Client
     */
    private $redmine;

    public function __construct(Client $redmine)
    {
        $this->redmine = $redmine;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $response = $this->redmine->get('users/current.json', [
                'auth' => [$token->getUsername(), $token->getCredentials()]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new AuthenticationException('Invalid credentials');
            }

            $user = new RedmineUser(json_decode($response->getBody(), true)['user']);

            return new UsernamePasswordToken(
                $user,
                $user->getApiKey(),
                $providerKey,
                $user->getRoles()
            );
        }
        catch (RequestException $e) {
            throw new AuthenticationException('Invalid credentials');
        }
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
