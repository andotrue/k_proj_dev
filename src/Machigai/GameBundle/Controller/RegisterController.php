<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegisterController extends Controller
{
    public function indexAction()
    {
        return $this->render('MachigaiGameBundle:Register:index.html.twig');
    }

    public function completeAction()
    {
        return $this->render('MachigaiGameBundle:Register:complete.html.twig');
    }

}
