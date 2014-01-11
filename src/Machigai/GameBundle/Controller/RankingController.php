<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RankingController extends Controller
{
    public function indexAction()
    {
	$em = $this->getDoctrine()->getManager();
	$ranking_this_month = $em->createQuery(
          'SELECT r 
           FROM MachigaiGameBundle:Ranking r
           WHERE r.year = 2013
       	   And r.month =12
           ORDER BY r.level asc , r.rank asc')->getResult();
/*  $ranking_user_this_month = $em->createQuery(
          'SELECT u 
           FROM MachigaiGameBundle:Ranking r
           WHERE r.year = 2013
           And r.month =12
           ORDER BY r.level asc , r.rank asc')->getResult();
*/
//  var_dump($ranking_this_month);
//  exit;
	return $this->render('MachigaiGameBundle:Ranking:index.html.twig',array('ranking_this_month' => $ranking_this_month));
    }
}