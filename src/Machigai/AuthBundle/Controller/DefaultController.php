<?php

namespace Machigai\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MachigaiAuthBundle:Default:index.html.twig', array('name' => $name));
    }
}
