<?php

namespace ApiManagerBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use ApiManagerBundle\ModelManager\UserModelManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder;

class LoginFormHandler
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var UserModelManager
     */
    protected $userModelManager;

    /**
     * @var UserPasswordEncoder
     */
    protected $securityPasswordEncoder;

    /**
     * @var LcobucciJWTEncoder
     */
    protected $jwtEncoder;

    protected $serializer;
    /**
     * LoginFormHandler constructor.
     * @param FormInterface $form
     * @param UserModelManager $userModelManager
     * @param UserPasswordEncoder $securityPasswordEncoder
     * @param LcobucciJWTEncoder $jwtEncoder
     */
    public function __construct(FormInterface $form,
                                UserModelManager $userModelManager,
                                UserPasswordEncoder $securityPasswordEncoder,
                                LcobucciJWTEncoder $jwtEncoder,
                                $serializer)
    {
        $this->userModelManager = $userModelManager;
        $this->form = $form;
        $this->securityPasswordEncoder = $securityPasswordEncoder;
        $this->jwtEncoder = $jwtEncoder;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function process(Request $request)
    {
        $this->form->handleRequest($request);
        if($this->form->isValid()) {
            $formData = $this->form->getData();
            if(!$formData['login']) {
                throw new NotFoundHttpException('login can not be null');
            }
            if(!$formData['password']) {
                throw new NotFoundHttpException('password can not be null');
            }

            $user = $this->userModelManager->getUserByLogin($formData['login']);
            $ckeck = $this->securityPasswordEncoder->isPasswordValid($user, $formData['password']);
            if(!$ckeck) {
                throw new NotFoundHttpException('The given password is invalid.');
            }
            $token = $this->jwtEncoder->encode([
                    'username' => $user->getUsername(),
                    'exp' => time() + 3600 // 1 hour expiration
                ]);

            $user->setToken($token);
            $this->userModelManager->update($user);
        } else {
            return $this->getErrorMessages($this->form);
        }

        return $user;
    }

    /**
     * @param \Symfony\Component\Form\Form $form
     * @return array
     */
    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}