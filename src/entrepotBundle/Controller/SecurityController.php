<?php

namespace entrepotBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function indexAction()
    {

        return $this->render('@entrepot/Default/index.html.twig');
    }
}
