<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Controller\BaseController;

class RankingController extends BaseController
{
    public function indexAction()
    {
    $month = date('n');
    $year = date('Y');
    $pre_year = $year-1;
    if($month == 1){
      $pre_month = 12;
    }else{
      $pre_month = $month-1;
    }
    $ranking_this_month = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Ranking')
        ->findBy(array('month'=>$month,'year'=>$year),array('rank'=>'ASC'));
    $ranking_previous_month = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Ranking')
        ->findBy(array('month'=>$pre_month,'year'=>$pre_year),array('rank'=>'ASC'));
	return $this->render('MachigaiGameBundle:Ranking:index.html.twig',array('ranking_this_month' => $ranking_this_month, 'ranking_previous_month' => $ranking_previous_month));
    }
}