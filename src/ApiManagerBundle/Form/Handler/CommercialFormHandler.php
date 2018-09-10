<?php

namespace ApiManagerBundle\Form\Handler;

use ApiManagerBundle\Entity\User;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;


class CommercialFormHandler
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * CommercialFormHandler constructor.
     * @param FormInterface $form
     */
    public function __construct($form)
    {
        $this->form = $form;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function process(Request $request)
    {
        $commercial = new User();
        $commercial->addRole('ROLE_COMMERCIAL');
        $this->form->setData($commercial);
        $this->form->handleRequest($request);
        if($request->getMethod() == "POST" && $this->form->isValid()) {

            dump($this->form->getData());die;
        }
        $process = false;
        return $process;
    }
}