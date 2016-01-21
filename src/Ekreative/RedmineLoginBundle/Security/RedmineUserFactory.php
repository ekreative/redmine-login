<?php
/**
 * Created by mcfedr on 7/30/15 11:40
 */

namespace Ekreative\RedmineLoginBundle\Security;

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
    public function get(array $data, $isAdmin)
    {
        return new $this->class($data, $isAdmin);
    }
}
