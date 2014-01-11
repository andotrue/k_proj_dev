<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GameController extends Controller
{
    public function selectAction()
    {
	return $this->render('MachigaiGameBundle:Game:select.html.twig');	
    }

    public function playAction()
    {
	return $this->render('MachigaiGameBundle:Game:index.html.twig');	
    }

    public function finishAction()
    {
	return $this->render('MachigaiGameBundle:Game:finish.html.twig');	
    }

    public function clearAction()
    {
	return $this->render('MachigaiGameBundle:Game:clear.html.twig');	
    }

    public function failAction()
    {
	return $this->render('MachigaiGameBundle:Game:fail.html.twig');	
    }

}
