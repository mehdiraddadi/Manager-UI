<?php

namespace ApiManagerBundle\ModelManager;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserModelManager
{
    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $userRepository;

    /**
     * UserModelManager constructor.
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->userRepository = $this->doctrine->getRepository('ApiManagerBundle:User');
    }

    /**
     * @param $login
     * @return mixed
     */
    public function getUserByLogin($login)
    {
        $user = $this->userRepository->findOneBy(array('username' => $login));
        if (!isset($user))
        {
            throw new NotFoundHttpException('Unable to find user.');
        }

        return $user;
    }
}