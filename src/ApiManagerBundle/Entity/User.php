<?php

namespace ApiManagerBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class User
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @JMS\ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $id;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $username;

    /**
     * @var string The email of the user.
     *
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @JMS\Expose
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @JMS\Expose
     */
    protected $lastname;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $token;

    /**
     * @JMS\Expose
     * @JMS\Type("collection")
     */
    protected $roles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lasname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
}