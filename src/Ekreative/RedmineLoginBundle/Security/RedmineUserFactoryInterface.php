<?php
/**
 * Created by mcfedr on 1/21/16 12:50
 */

namespace Ekreative\RedmineLoginBundle\Security;

interface RedmineUserFactoryInterface
{
    /**
     * @param array $data
     * @param bool $isAdmin
     * @return RedmineUser
     */
    public function get(array $data, $isAdmin);
}
