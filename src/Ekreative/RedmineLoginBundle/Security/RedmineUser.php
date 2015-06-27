<?php
/**
 * Created by mcfedr on 27/06/15 11:30
 */

namespace Ekreative\RedmineLoginBundle\Security;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RedmineUser implements UserInterface, EquatableInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $lastLoginAt;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var bool
     */
    private $status;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setId($data['id'])
            ->setUsername($data['login'])
            ->setFirstName($data['firstname'])
            ->setLastName($data['lastname'])
            ->setEmail($data['mail'])
            ->setCreatedAt(new \DateTime($data['created_on']))
            ->setLastLoginAt(new \DateTime($data['last_login_on']))
            ->setApiKey($data['api_key']);
        if (array_key_exists('status', $data)) {
            $this->setStatus($data['status']);
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return RedmineUser
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return RedmineUser
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return RedmineUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return RedmineUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return RedmineUser
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return RedmineUser
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * @param \DateTime $lastLoginAt
     * @return RedmineUser
     */
    public function setLastLoginAt($lastLoginAt)
    {
        $this->lastLoginAt = $lastLoginAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return RedmineUser
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $status
     * @return RedmineUser
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_REDMINE'];
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof RedmineUser) {
            return false;
        }

        return $user->getId() == $this->getId();
    }
}
