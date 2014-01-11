<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RankingController extends Controller
{
    public function indexAction()
    {
    $month = date('n');
    if($month == 1){
      $pre_month = 12;
    }else{
      $pre_month = $month-1;
    }  
    $ranking_this_month = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Ranking')
        ->findBy(array('month'=>$month));
    $ranking_previous_month = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Ranking')
        ->findBy(array('month'=>$pre_month));
/*  
	$em = $this->getDoctrine()->getManager();
	$ranking_this_month = $em->createQuery(
          'SELECT r 
           FROM MachigaiGameBundle:Ranking r
           WHERE r.year = 2013
       	   And r.month =12
           ORDER BY r.level asc , r.rank asc')->getResult();
*/
	return $this->render('MachigaiGameBundle:Ranking:index.html.twig',array('ranking_this_month' => $ranking_this_month, 'ranking_previous_month' => $ranking_previous_month));
    }
}