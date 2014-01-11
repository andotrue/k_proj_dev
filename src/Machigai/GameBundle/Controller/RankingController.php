<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RankingController extends Controller
{
    public function indexAction()
    {
    $month = date('n');
    $year = date('Y');
    if($month == 1){
      $year = $year-1;
      $pre_month = 12;
    }else{
      $pre_month = $month-1;
    }  
    $ranking_this_month = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Ranking')
        ->findBy(array('month'=>$month,'year'=>$year));
    $ranking_previous_month = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Ranking')
        ->findBy(array('month'=>$pre_month,'year'=>$year));
    $aaa = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:User')
        ->findAll();
	return $this->render('MachigaiGameBundle:Ranking:index.html.twig',array('ranking_this_month' => $ranking_this_month, 'ranking_previous_month' => $ranking_previous_month));
    }
}