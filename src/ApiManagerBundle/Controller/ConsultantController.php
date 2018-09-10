<?php

namespace ApiManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConsultantController extends Controller
{
    public function postConsultantAction(Request $request)
    {
        return new Response(' add consultant');
    }
}