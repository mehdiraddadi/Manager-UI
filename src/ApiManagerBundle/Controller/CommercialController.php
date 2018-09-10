<?php

namespace ApiManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;


class CommercialController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function postManagerAction(Request $request)
    {
        $handlerCommercial = $this->get('api_manager.commercial.form.handler');
        $handlerCommercial->process($request);

        $view = View::create()
            ->setStatusCode(200)
            ->setData('hello manager');

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}