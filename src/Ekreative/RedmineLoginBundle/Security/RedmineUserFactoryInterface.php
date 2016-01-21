<?php
/**
 * Created by mcfedr on 1/21/16 12:50
 */

namespace Ekreative\RedmineLoginBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;

interface RedmineUserFactoryInterface
{
    /**
     * @param array $data
     * @param bool $isAdmin
     * @return UserInterface
     */
    public function loadUserByData(array $data, $isAdmin);

    public function refreshUser(UserInterface $user);

    public function supportsClass($class);
}
