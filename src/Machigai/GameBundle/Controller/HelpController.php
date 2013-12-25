<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelpController extends Controller
{
    public function indexAction()
    {
	return $this->render('MachigaiGameBundle:Help:index.html.twig');
    }

    public function howtoplayAction()
    {
	return $this->render('MachigaiGameBundle:Help:howtoplay.html.twig');
    }

    public function termsAction()
    {
	return $this->render('MachigaiGameBundle:Help:terms.html.twig');
    }

    public function inquiryAction()
    {
	return $this->render('MachigaiGameBundle:Help:inquiry.html.twig');
    }

}
