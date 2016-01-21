<?php
/**
 * Created by mcfedr on 7/30/15 11:40
 */

namespace Ekreative\RedmineLoginBundle\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class RedmineUserFactory implements RedmineUserFactoryInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class Name of class to construct
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @param array $data
     * @param bool $isAdmin
     * @return RedmineUser
     */
    public function loadUserByData(array $data, $isAdmin)
    {
        return new $this->class($data, $isAdmin);
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
        return is_subclass_of($class, $this->class);
    }
}
