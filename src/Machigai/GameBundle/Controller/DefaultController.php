<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MachigaiGameBundle:Default:index.html.twig', array('name' => 'taro'));
    }
    public function errorAction()
    {
	return $this->render('MachigaiGameBundle:Default:error.html.twig');
    }
}
