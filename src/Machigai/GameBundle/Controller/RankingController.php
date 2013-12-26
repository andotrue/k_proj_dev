<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RankingController extends Controller
{
    public function indexAction()
    {
	return $this->render('MachigaiGameBundle:Ranking:index.html.twig');
    }

}
