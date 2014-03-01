<?php

namespace Machigai\GameBundle\Controller;

use Machigai\GameBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class RankingController extends BaseController
{
    public function indexAction()
    {
    $month = date('n');
    $year = date('Y');
    if($month == 1){
      $pre_year = $year-1;
      $pre_month = 12;
    }else{
      $pre_year = $year;
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

	// 月初に実行されることを想定
	public function summaryAction(){

		$bonus_point = 500;
		
		$em = $this->getDoctrine()->getEntityManager();
		//　トランザクション開始
		$em->getConnection()->beginTransaction();
		
		try {
			$month = date('n');
			$year = date('Y');
			if($month == 1){
			  $pre_year = $year-1;
			  $pre_month = 12;
			}else{
			  $pre_year = $year;
			  $pre_month = $month-1;
			}

 			// 先月のランキングを取得
			$ranking_previous_month = $this->getDoctrine()
				 ->getRepository('MachigaiGameBundle:Ranking')
				 ->findBy(
						 array(
							'month'=>$pre_month,
							'year'=>$pre_year,
							'bonusPoint'=>null),
						 array(
							'rank'=>'ASC'),
						 10);
			
			// 取得者にポイントを加算
			foreach($ranking_previous_month as $data){
				
				$data->setBonusPoint($bonus_point);
				$user = $data->getUser();
				$user->setCurrentPoint($user->getCurrentPoint() + $bonus_point);
				
				$em->persist($data);
				$em->persist($user);
			}
		
			// トランザクション終了
			$em->getConnection()->commit();
			$em->flush();
		} catch (ErrorException $ex){
			$em->getConnection()->rollback();
		}
		
		$em->close();
		return new Response('ok');
	}
}