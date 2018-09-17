<?php

namespace ApiManagerBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\LcobucciJWTEncoder;
use ApiManagerBundle\ModelManager\UserModelManager;

class CheckLoggedHandler
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var LcobucciJWTEncoder
     */
    protected $jwtEncoder;

    /**
     * @var UserModelManager
     */
    protected $userModelManager;

    /**
     * LoginFormHandler constructor.
     * @param FormInterface $form
     */
    public function __construct(FormInterface $form,
                                LcobucciJWTEncoder $jwtEncoder,
                                UserModelManager $userModelManager)
    {
        $this->form = $form;
        $this->jwtEncoder = $jwtEncoder;
        $this->userModelManager = $userModelManager;
    }

    /**
     * @param Request $request
     * @return bool|mixed
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function isLogged(Request $request)
    {
        $this->form->handleRequest($request);
        if($this->form->isValid()) {
            $formData = $this->form->getData();
            if(!$formData['username']) {
                throw new NotFoundHttpException('username can not be null');
            }
            if(!$formData['token']) {
                throw new NotFoundHttpException('token can not be null');
            }
            $user = $this->userModelManager->getUserByLogin($formData['username']);
            if($user->getToken() === $formData['token']) {
                $data = $this->jwtEncoder->decode($formData['token']);
                if ($data === false) {
                    throw new NotFoundHttpException('Invalid Token');
                }
                return true;
            }
            return false;
        } else {
            return $this->getErrorMessages($this->form);
        }
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