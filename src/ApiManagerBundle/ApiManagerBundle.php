<?php

namespace ApiManagerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApiManagerBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
