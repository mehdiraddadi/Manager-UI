<?php

namespace ApiManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class ApiUserController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function loginAction(Request $request)
    {
        $handlerLogin = $this->get('api_manager.generic.login.handler');
        $process = $handlerLogin->process($request);

        $view = View::create()
            ->setStatusCode(200)
            ->setData($process);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    public function registerAction()
    {
        return new Response('register from api');
    }

    public function isAuthentificated(Request $request)
    {

    }
}